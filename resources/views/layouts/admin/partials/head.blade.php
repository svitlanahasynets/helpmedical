<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Styles -->

<link rel="stylesheet" href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/plugins/select2/select2.css">
<link rel="stylesheet" href="/assets/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/plugins/uniform/css/uniform.default.min.css" rel="stylesheet">

<!-- metro theme -->
<link rel="stylesheet" href="/assets/plugins/metro/metro.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/plugins/metro/metro-icons.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/plugins/metro/metro-responsive.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/plugins/metro/metro-schemes.css" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link href="/assets/plugins/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css"/>

<!-- <link rel="stylesheet" type="text/css" href="/assets/plugins/jquery-nestable/jquery.nestable.css"/> -->


<link rel="stylesheet" href="/assets/css/vendor.css">
<link rel="stylesheet" href="/assets/css/global/fonts.css">
<link rel="stylesheet" href="/assets/css/global/components.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/global/plugins.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/layout/layout.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/layout/admin/app.css" rel="stylesheet">

<!-- custom link -->
<link rel="stylesheet" href="{{ url('assets/css/pages/admin/'.str_replace('.', '/', $page).'.css') }}">
