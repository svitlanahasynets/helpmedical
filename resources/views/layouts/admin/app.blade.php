<!DOCTYPE html>
<html lang="en">
<head>

    @yield('title')
    @include('layouts/admin/partials/head')
    @yield('additional_css')

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

</head>

<body>

    @include('layouts/admin/partials/header')
    @include('layouts/section/script')

    <!-- BEGIN CONTAINER -->
    <div class="page-container">

        <!-- BEGIN SIDEBAR -->
        @include('layouts/admin/partials/sidebar')
        <!-- END SIDEBAR -->

        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <div class="page-content">
                <!-- BEGIN PAGE HEADER-->
                
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="page-title">
                            {{ $main_title }}
                        </h3>
                    </div>
                </div>
                <!-- END PAGE HEADER-->

                @if (!$errors->isEmpty())
                    <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>                    
                    @endforeach
                    </div>
                @endif

                @if (session('fail'))
                    <div class="alert alert-danger">
                        {{ session('fail') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
        <!-- END CONTENT -->

    </div>

    @include('layouts/admin/partials/footer')

    @yield('additional_js')

</body>
</html>
