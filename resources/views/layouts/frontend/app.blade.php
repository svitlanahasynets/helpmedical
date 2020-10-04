<!DOCTYPE html>
<html lang="en">
<head>

    @yield('title')
    @include('layouts/frontend/partials/head')
    @yield('additional_css')

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

</head>

<body>
    @include('layouts/section/script')
    <!-- BEGIN CONTAINER -->
    <div class="homepage-container">
        @include('layouts/frontend/partials/header')

        <!-- BEGIN CONTENT -->
        <div class="homepage-content-wrapper">
            <div class="homepage-content">
                @yield('content')
            </div>
        </div>
        <!-- END CONTENT -->
        @include('layouts/frontend/partials/footer')
    </div>

    @include('layouts/frontend/partials/foot')

    @yield('additional_js')

</body>
</html>
