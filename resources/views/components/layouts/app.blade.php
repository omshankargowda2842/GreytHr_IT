<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/it-xpert.png') }}">
    <title>IT Expert</title>

    @livewireStyles
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css?v=' . filemtime(public_path('css/app.css'))) }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/swiper/swiper-bundle.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- <script src="https://unpkg.com/alpinejs" defer></script> -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css?v=' . filemtime(public_path('css/app.css'))) }}">
    <link rel="stylesheet" href="{{ asset('css/it-employee.css?v=' . filemtime(public_path('css/it-employee.css'))) }}">

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
        @livewireScripts



        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
        <script src="{{ asset('js/admin-dash.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/@floating-ui/core@1.6.7"></script>
        <script src="https://cdn.jsdelivr.net/npm/@floating-ui/dom@1.6.10"></script>
        <script>
            function toggleSidebar() {
                document.body.classList.toggle('sidebar-toggled');
            }

            function scrollFun() {
                var navbar = document.getElementById('customNav');
                var mainContent = document.getElementById('maincontent');

                if (mainContent.scrollTop > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            }
        </script>
    </body>
@endguest

</html>
