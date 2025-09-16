<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'ensure.admin']);
    }

    /**
     * List pengguna dengan filter ringan.
     * Query:
     *  - q: cari di name/email (ilike)
     *  - role: admin|user
     */
    public function index(Request $request)
    {
        $role = $request->string('role')->toString();
        $q    = User::query()
            ->when($request->filled('q'), function ($x) use ($request) {
                $term = $request->string('q')->toString();
                $x->where(function ($y) use ($term) {
                    $y->where('name', 'ilike', "%{$term}%")
                      ->orWhere('email', 'ilike', "%{$term}%");
                });
            })
            ->when(in_array($role, ['admin','user'], true), function ($x) use ($role) {
                $x->where('is_admin', $role === 'admin');
            })
            ->orderByDesc('is_admin')
            ->orderBy('name');

        $users = $q->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /** Form edit user. */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update profil user:
     * - name, email
     * - is_admin (boolean)
     * - (opsional) learning state: current_bab/current_track/current_step
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'           => ['required','string','max:255'],
            'email'          => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'is_admin'       => ['sometimes','boolean'],

            // learning state (opsional)
            'current_bab'    => ['nullable','integer','min:1','max:99'],
            'current_track'  => ['nullable', Rule::in(['A','B', null])],
            'current_step'   => ['nullable','integer','min:1','max:5'],
            'progress'       => ['nullable','numeric','min:0','max:100'],
        ]);

        // cegah admin non-super men-downgrade dirinya sendiri tanpa sadar (opsional)
        if ($user->id === Auth::id() && array_key_exists('is_admin', $data) && !$data['is_admin']) {
            return back()->withInput()->withErrors(['is_admin' => 'Kamu tidak bisa mencabut hak admin dari akunmu sendiri.']);
        }

        // normalisasi track
        if (array_key_exists('current_track', $data)) {
            $t = $data['current_track'];
            $data['current_track'] = $t ? strtoupper($t) : null;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('status', 'User diperbarui ✅');
    }

    /**
     * Reset password user (admin action).
     * Body: password (min:8) + password_confirmation
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required','string','min:8','confirmed'],
        ]);

        // aman: hash
        $user->update([
            'password' => Hash::make($request->string('password')),
        ]);

        return back()->with('status', 'Password direset ✅');
    }

    /**
     * Toggle is_admin cepat (AJAX atau tombol).
     * (Cegah self-demote)
     */
    public function toggleAdmin(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['is_admin' => 'Tidak bisa mengubah status admin diri sendiri.']);
        }

        $user->update(['is_admin' => !$user->is_admin]);

        return back()->with('status', 'Status admin diperbarui ✅');
    }

    /**
     * Hapus user (cegah hapus diri sendiri).
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['delete' => 'Tidak bisa menghapus akunmu sendiri.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'User dihapus 🗑️');
    }
}
