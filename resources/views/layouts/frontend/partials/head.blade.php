<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel='shortcut icon' type='image/x-icon' href='/favicon.ico' />

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Styles -->

<link rel="stylesheet" href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/plugins/select2/select2.css">
<link rel="stylesheet" href="/assets/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/plugins/uniform/css/uniform.default.min.css" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link href="/assets/plugins/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css"/>

<!-- fancy box start -->
<link href="/assets/plugins/fancybox/dist/jquery.fancybox.min.css" rel="stylesheet" type="text/css"/>
<link href="/assets/plugins/fancybox/src/css/thumbs.css" rel="stylesheet" type="text/css"/>
<!-- fancy box end -->

<!-- video plugin include -->
<link rel="stylesheet" href="/assets/css/global/video-default.css">

<link rel="stylesheet" href="/assets/css/vendor.css">
<link rel="stylesheet" href="/assets/css/global/fonts.css">
<link rel="stylesheet" href="/assets/css/global/components.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/global/plugins.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/layout/layout.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/layout/front/app.css" rel="stylesheet">

<!-- custom link -->
<link rel="stylesheet" href="{{ url('assets/css/pages/frontend/'.str_replace('.', '/', $page).'.css') }}">
