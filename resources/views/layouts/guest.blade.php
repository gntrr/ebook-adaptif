<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!--==============================
        All CSS File From EdMate Template
        ============================== -->
        <!-- Bootstrap -->
        <link rel="stylesheet" href="{{ asset('edmate/assets/css/bootstrap.min.css') }}">
        <!-- file upload -->
        <link rel="stylesheet" href="{{ asset('edmate/assets/css/file-upload.css') }}">
        <!-- file upload -->
        <link rel="stylesheet" href="{{ asset('edmate/assets/css/plyr.css') }}">
        <!-- DataTables -->
        <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
        <!-- full calendar -->
        <link rel="stylesheet" href="{{ asset('edmate/assets/css/full-calendar.css') }}">
        <!-- jquery Ui -->
        <link rel="stylesheet" href="{{ asset('edmate/assets/css/jquery-ui.css') }}">
        <!-- editor quill Ui -->
        <link rel="stylesheet" href="{{ asset('edmate/assets/css/editor-quill.css') }}">
        <!-- apex charts Css -->
        <link rel="stylesheet" href="{{ asset('edmate/assets/css/apexcharts.css') }}">
        <!-- calendar Css -->
        <link rel="stylesheet" href="{{ asset('edmate/assets/css/calendar.css') }}">
        <!-- jvector map Css -->
        <link rel="stylesheet" href="{{ asset('edmate/assets/css/jquery-jvectormap-2.0.5.css') }}">
        <!-- Main css -->
        <link rel="stylesheet" href="{{ asset('edmate/assets/css/main.css') }}">

        <!-- Scripts -->
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    </head>
    <body>

        <!--==================== Preloader Start ====================-->
        <div class="preloader">
            <div class="loader"></div>
        </div>
        <!--==================== Preloader End ====================-->

        <!--==================== Sidebar Overlay End ====================-->
        <div class="side-overlay"></div>
        <!--==================== Sidebar Overlay End ====================-->
     
        {{ $slot }}
    </body>

    <!--==============================
    All JS File From EdMate Template
    ============================== -->
    <!-- Jquery js -->
    <script src="{{ asset('edmate/assets/js/jquery-3.7.1.min.js') }}"></script>
    <!-- Bootstrap Bundle Js -->
    <script src="{{ asset('edmate/assets/js/boostrap.bundle.min.js') }}"></script>
    <!-- Phosphor Js -->
    <script src="{{ asset('edmate/assets/js/phosphor-icon.js') }}"></script>
    <!-- file upload -->
    <script src="{{ asset('edmate/assets/js/file-upload.js') }}"></script>
    <!-- file upload -->
    <script src="{{ asset('edmate/assets/js/plyr.js') }}"></script>
    <!-- dataTables -->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <!-- full calendar -->
    <script src="{{ asset('edmate/assets/js/full-calendar.js') }}"></script>
    <!-- jQuery UI -->
    <script src="{{ asset('edmate/assets/js/jquery-ui.js') }}"></script>
    <!-- jQuery UI -->
    <script src="{{ asset('edmate/assets/js/editor-quill.js') }}"></script>
    <!-- apex charts -->
    <script src="{{ asset('edmate/assets/js/apexcharts.min.js') }}"></script>
    <!-- Calendar Js -->
    <script src="{{ asset('edmate/assets/js/calendar.js') }}"></script>
    <!-- jvectormap Js -->
    <script src="{{ asset('edmate/assets/js/jquery-jvectormap-2.0.5.min.js') }}"></script>
    <!-- jvectormap world Js -->
    <script src="{{ asset('edmate/assets/js/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- main js --> <script src="{{ asset('edmate/assets/js/main.js') }}"></script>
</html>
