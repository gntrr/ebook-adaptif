<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Evaluasi;
use App\Models\HasilEvaluasi;
use App\Services\LearningPathService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluasiController extends Controller
{
    /**
     * Submit evaluasi untuk sebuah slot (tipe: 'evaluasi' pada kombinasi bab/track/step).
     * Route: POST /eval/{materi}/submit
     * Body:
     *  - Skenario 1 (satu soal):      answer=A|B|C|D
     *  - Skenario 2 (banyak soal):    answers[evaluasi_id]=A|B|C|D (array)
     *  - Fallback (dev sementara):    skor=0..100
     */
    public function submitStep(Materi $materi, Request $req, LearningPathService $svc)
    {
        $this->authorizePosition($materi);

        // Ambil semua soal yang terkait materi evaluasi ini
        $questions = Evaluasi::where('materi_id', $materi->id)->get();

        [$skor, $lulus, $evaluasiIdForRecord] = $this->grade($req, $questions);

        // Simpan hasil (satu rekaman ringkas per submit)
        HasilEvaluasi::create([
            'user_id'     => Auth::id(),
            'evaluasi_id' => $evaluasiIdForRecord,
            'skor'        => $skor,
            'lulus'       => $lulus,
        ]);

        // Tentukan tujuan berikutnya
        $next = $svc->nextAfterStepEval(Auth::user(), $materi->bab, $materi->track, $materi->step, $skor);

        // Update posisi user
        $this->updateUserState($next);

        // Redirect sesuai mode
        return $this->redirectNext($next)->with('status', $lulus
            ? 'Mantap! Kamu lulus evaluasi step ini.'
            : 'Belum lolos, ayo coba ulangi materinya 💪');
    }

    /**
     * Submit evaluasi bab (checkpoint akhir bab).
     * Route: POST /eval-bab/{bab}/{track?}
     * Body:
     *  - Sama seperti submitStep: answer / answers[] / skor
     */
    public function submitBab(int $bab, ?string $track, Request $req, LearningPathService $svc)
    {
        $track = $this->normalizeTrack($track);

        // Cari materi bertipe 'evaluasi_bab' untuk bab/track ini
        $evalMateri = Materi::where('bab', $bab)
            ->when($track, fn($q) => $q->where('track', $track), fn($q) => $q->whereNull('track'))
            ->where('step', 5)
            ->where('tipe', 'evaluasi_bab')
            ->firstOrFail();

        $this->authorizePosition($evalMateri);

        $questions = Evaluasi::where('materi_id', $evalMateri->id)->get();
        [$skor, $lulus, $evaluasiIdForRecord] = $this->grade($req, $questions);

        HasilEvaluasi::create([
            'user_id'     => Auth::id(),
            'evaluasi_id' => $evaluasiIdForRecord,
            'skor'        => $skor,
            'lulus'       => $lulus,
        ]);

        $next = $svc->nextAfterBabEval(Auth::user(), $bab, $track, $skor);
        $this->updateUserState($next);

        return $this->redirectNext($next)->with('status', $lulus
            ? 'Nice! Evaluasi bab lulus. Lanjut ke materi berikutnya.'
            : 'Skor belum memenuhi. Coba ulangi bab ini dulu ya 🙏');
    }

    /* =========================
       Helpers (private)
       ========================= */

    /** Hitung skor submission (mendukung 1 soal atau banyak soal). */
    private function grade(Request $req, $questions): array
    {
        // 1) Banyak soal: answers[eid] = 'A'|'B'|'C'|'D'
        if ($req->has('answers') && is_array($req->input('answers'))) {
            $answers = $req->input('answers');

            $total = max(1, $questions->count());
            $benar = 0;
            $firstId = $questions->first()?->id ?? null;

            foreach ($questions as $q) {
                $jawab = $answers[$q->id] ?? null;
                if ($jawab !== null && $this->isCorrect($q, $jawab)) {
                    $benar++;
                }
            }

            $skor = (int) round(($benar / $total) * 100);
            return [$skor, $skor >= 60, $firstId ?? 0];
        }

        // 2) Satu soal: answer = 'A'|'B'|'C'|'D'
        if ($req->filled('answer') && $questions->count() >= 1) {
            $q = $questions->first();
            $jawab = (string) $req->input('answer');
            $skor = $this->isCorrect($q, $jawab) ? 100 : 0;
            return [$skor, $skor >= 60, $q->id];
        }

        // 3) Fallback dev (terima skor manual)
        $req->validate([
            'skor' => 'required|integer|min:0|max:100',
        ]);
        $skor = (int) $req->input('skor');
        $firstId = $questions->first()?->id ?? 0;

        return [$skor, $skor >= 60, $firstId];
    }

    /** Cek benar/salah; dukung dua skema:
     *  - Kolom opsi A–D: opsi_a..opsi_d + jawaban_benar (TEXT)
     *  - JSON opsi: 'opsi' = ["A","B",...], 'jawaban_benar' = "A"
     */
    private function isCorrect(Evaluasi $q, string $jawab): bool
    {
        $jawab = strtoupper(trim($jawab));

        // Skema kolom A–D (jika ada kolom 'opsi_a')
        if (array_key_exists('opsi_a', $q->getAttributes())) {
            return strtoupper((string) $q->jawaban_benar) === $jawab;
        }

        // Skema JSON (opsi & jawaban_benar)
        if (array_key_exists('opsi', $q->getAttributes())) {
            return strtoupper((string) $q->jawaban_benar) === $jawab;
        }

        // Default: anggap benar kalau string sama persis
        return strtoupper((string) $q->jawaban_benar) === $jawab;
    }

    /** Normalisasi track dari URL. */
    private function normalizeTrack(?string $track): ?string
    {
        if ($track === null) return null;
        $track = strtoupper(trim($track));
        return $track === 'A' || $track === 'B' ? $track : null;
    }

    /** Pastikan user tidak menilai posisi yang belum dibuka (opsional; bisa dipindah ke middleware). */
    private function authorizePosition(Materi $materi): void
    {
        $u = Auth::user();
        if (!$u) return;

        // Sederhana: larang kalau bab request > current bab user
        if ($materi->bab > ($u->current_bab ?? 1)) {
            abort(403, 'Belum saatnya mengerjakan evaluasi ini.');
        }
    }

    /** Update posisi user setelah keputusan jalur. */
    private function updateUserState(array $next): void
    {
        Auth::user()->update([
            'current_bab'   => $next['bab'],
            'current_track' => $next['track'] ?? null,
            'current_step'  => $next['step'],
            'progress'      => $this->calcProgress($next['bab'], $next['track'] ?? null, $next['step']),
        ]);
    }

    /** Redirect helper. */
    private function redirectNext(array $next)
    {
        if (($next['mode'] ?? null) === 'evaluasi_bab') {
            // Arahkan ke halaman evaluasi bab (kamu bisa ganti ke view khusus)
            return redirect()->route('evaluasi_bab.submit', [$next['bab'], $next['track'] ?? null]);
        }

        return redirect()->route('materi.show', [
            $next['bab'],
            $next['track'] ?? null,
            $next['step'],
        ]);
    }

    /** Hitung progress sederhana (placeholder; nanti bisa ganti pakai real formula). */
    private function calcProgress(int $bab, ?string $track, int $step): float
    {
        // Contoh kasar: konversi posisi jadi persen (maks 6 bab * ~5 step)
        $totalSteps = 6 * 5;
        $pos = (($bab - 1) * 5) + min($step, 5);
        return round(($pos / $totalSteps) * 100, 2);
    }
}
