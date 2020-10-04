<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('title')

    <!-- Styles -->

    <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet">
    <link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/assets/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">

    <link href="/assets/css/global/components.css" rel="stylesheet">
    <link href="/assets/css/global/plugins.css" rel="stylesheet">
    <link href="/assets/css/layout/layout.css" rel="stylesheet">
    <link href="/assets/css/layout/themes/darkblue.css" rel="stylesheet">

    <link href="/assets/css/pages/login.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    
    @yield('content')

    <!-- Scripts -->
    <script src="/assets/plugins/jquery.min.js"></script>
    <script src="/assets/plugins/jquery-validation/js/jquery.validate.min.js"></script>
    <script src="/assets/plugins/backstretch/jquery.backstretch.min.js"></script>
    <script src="/assets/plugins/jquery.blockui.min.js"></script>
    <script src="/assets/plugins/jquery.form.js"></script>

    <script src="/assets/js/metronic.js"></script>
    <script src="/assets/js/login.js"></script>

</body>
</html>
