<!DOCTYPE html>
<html lang="en">

<head>
    @stack('css')
    @include('layout.header')
</head>

<body>
    @yield('content')

    @include('layout.script')
    @stack('js')
</body>
</html>
