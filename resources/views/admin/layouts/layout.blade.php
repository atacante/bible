<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $page_title or "AdminLTE Dashboard" }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="{{ asset("/themes/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css"/>
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <!-- Ionicons -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->
    <link href="{{ asset("/themes/admin-lte/dist/css/AdminLTE.min.css")}}" rel="stylesheet" type="text/css"/>
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link href="{{ asset("/themes/admin-lte/dist/css/skins/skin-blue.min.css")}}" rel="stylesheet" type="text/css"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    {!!Html::style('js/dropzone/dist/min/basic.min.css')!!}
    {!!Html::style('js/dropzone/dist/min/dropzone.min.css')!!}
    {!!Html::style('js/bootstrap-datepicker/css/bootstrap-datepicker3.css')!!}
    {!!Html::style('//cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css')!!}
    {!!Html::style('css/admin-style.css')!!}
</head>
<body class="skin-blue layout-top-nav">
<div class="wrapper">

    <!-- Header -->
    @include('admin.partials.headertop')

            <!-- Sidebar -->
    {{--    @include('admin.partials.sidebar')--}}

            <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    {{ $page_title or "Page Title" }}
                    <small>{{ $page_description or null }}</small>
                </h1>
                <!-- You can dynamically generate breadcrumbs here -->
                {{--<ol class="breadcrumb">--}}
                    {{--<li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>--}}
                    {{--<li class="active">Here</li>--}}
                {{--</ol>--}}
                @yield('breadcrumbs')
            </section>

            <!-- Main content -->
            <section class="content">
                @notification()
                <!-- Your Page Content Here -->
                @yield('content')
                @include('admin.partials.deletepop')
                @include('admin.partials.popup')
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->
    </div>

    <!-- Footer -->
    @include('admin.partials.footer')

</div><!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.1.3 -->
<script src="{{ asset ("/themes/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="{{ asset ("/themes/admin-lte/bootstrap/js/bootstrap.min.js") }}" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="{{ asset ("/themes/admin-lte/dist/js/app.min.js") }}" type="text/javascript"></script>

{!!Html::script('/vendor/unisharp/laravel-ckeditor/ckeditor.js')!!}
{!!Html::script('/vendor/unisharp/laravel-ckeditor/adapters/jquery.js')!!}
{!!Html::script('//cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js')!!}

{{--<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>--}}
{{--{!!Html::script(asset('vendor/jsvalidation/js/jsvalidation.js'))!!}--}}
{{--{!! isset($jsValidator)?$jsValidator:'' !!}--}}
<script>
    if($("#symbolism,#location-desc").length > 0){
        $('#symbolism,#location-desc').ckeditor({
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()}}'
        });
    }
</script>
{!!Html::script('js/clipboard.min.js')!!}
{!!Html::script('js/main.js')!!}
{!!Html::script('js/functions.js')!!}

{!!Html::script('js/dropzone/dist/min/dropzone.min.js')!!}
{!!Html::script('js/bootstrap-datepicker/js/bootstrap-datepicker.min.js')!!}

<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience -->
</body>
</html>