<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @livewireStyles
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">

</head>
@guest
<livewire:it-login />
@else
<body>
    <div class="container-fluid p-0">
        <livewire:header />
        <div class="">
            <div class="sidebar-col1">
                <livewire:sidebar />
            </div>
            <div class="sidebar-col2">{{$slot}}</div>
        </div>
    </div>
    @livewireScripts

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+5hb7W5DF8t7zD0hB0tkL2vKqFztOaA5Q/lSzW8" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+DjW3e3ZfEj2We2t3nEzRZHfT5F/e" crossorigin="anonymous"></script>


    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-toggled');
        }

        // document.addEventListener('click', function(event) {
        //     var col1 = document.getElementById('sidebar-col1');
        //     if (!col1.contains(event.target) && !event.target.matches('.tabs')) {
        //         col1.classList.add('sidebar-toggled');
        //     }
        // });
    </script>
</body>
@endguest

</html>