<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/it-xpert.png') }}">
    <title>{{ $title ?? 'IT Expert' }}</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css?v=' . filemtime(public_path('css/app.css'))) }}">
    <link rel="stylesheet" href="{{ asset('css/it-employee.css?v=' . filemtime(public_path('css/it-employee.css'))) }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Swiper -->
    <link href="https://unpkg.com/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @livewireStyles
</head>

<body>

    @guest
        {{ $slot }}
    @else
        <section>
            @livewire('main-layout')
            <main id="maincontent" style="overflow: auto; height: calc(100vh - 65px);" onscroll="scrollFun()">
                {{ $slot }}
            </main>
        </section>
    @endguest

    <!-- Bootstrap JS (Latest) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Floating UI -->
    <script src="https://cdn.jsdelivr.net/npm/@floating-ui/core@1.6.7"></script>
    <script src="https://cdn.jsdelivr.net/npm/@floating-ui/dom@1.6.10"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/admin-dash.js') }}"></script>

    @livewireScripts

    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-toggled');
        }

        function scrollFun() {
            var navbar = document.getElementById('customNav');
            var mainContent = document.getElementById('maincontent');

            if (navbar && mainContent.scrollTop > 50) {
                navbar.classList.add('scrolled');
            } else if (navbar) {
                navbar.classList.remove('scrolled');
            }
        }
    </script>

</body>

</html>
