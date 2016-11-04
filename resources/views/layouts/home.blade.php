<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @section('title')
        <title> Bible Study Company: An online study Bible and social community </title>
    @show
    {{--<title>Bible Study Company: An online study Bible and social community</title>--}}
    @section('meta_keywords')
        <meta name="keywords" content="Bible Study Company"/>
    @show @section('meta_author')
        <meta name="author" content="Bible Study Company"/>
    @show @section('meta_description')
        <meta name="description" content="Bible Study Company - ONLINE STUDY BIBLE COMMUNITY - Studying Scripture to Live a Praiseworthy Life to God"/>
    @show @section('meta_og')
        <meta property="og:title" content="Bible Study Company: An online study Bible and social community" />
        <meta property="og:image" content="{!! url('/images/meta_logo.png') !!}" />
        {{--<meta property="og:image:secure_url" content="{!! url('/images/logo.png') !!}" />--}}
        <meta itemprop="og:description" property="og:description" content="Bible Study Company - ONLINE STUDY BIBLE COMMUNITY - Studying Scripture to Live a Praiseworthy Life to God" />
        <meta property="fb:app_id" content="848687605263767" />
    @show @section('meta_twitter')
        <meta property="twitter:card" content="summary">
        <meta property="twitter:title" content="Bible Study Company: An online study Bible and social community">
        <meta property="twitter:image" content="{!! url('/images/meta_logo.png') !!}">
        <meta property="twitter:description" content="Bible Study Company - ONLINE STUDY BIBLE COMMUNITY - Studying Scripture to Live a Praiseworthy Life to God">
    @show
    <link rel="image_src" href="{!! url('/images/logo.png') !!}"/>
        {!!Html::style('//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.0/css/font-awesome.min.css')!!}
        {!!Html::style('//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css')!!}
        {!!Html::style('//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css')!!}
        {!!Html::style('js/bootstrap-datepicker/css/bootstrap-datepicker3.css')!!}
{{--        {!!Html::style('//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css')!!}--}}
        {!!Html::style('js/dropzone/dist/min/basic.min.css')!!}
        {!!Html::style('js/dropzone/dist/min/dropzone.min.css')!!}

        {{-- {!!Html::style(Asset::v('/css/style.css'))!!} --}}
        {{-- {!!Html::style(Asset::v('/css/style.css'))!!} --}}
        {!!Html::style(Asset::v('/css/app.css'))!!}

        {!!Html::style('css/ionicons-2.0.1/css/ionicons.min.css')!!}

        {!!Html::script('//code.jquery.com/jquery-2.2.0.min.js')!!}
        {!!Html::script('//code.jquery.com/ui/1.11.4/jquery-ui.js')!!}
        {!!Html::script('//cdn.jsdelivr.net/jquery.scrollto/2.1.2/jquery.scrollTo.min.js')!!}
        {!!Html::script('//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js')!!}
        {!!Html::script('//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js')!!}
        {!!Html::script('js/dropzone/dist/min/dropzone.min.js')!!}
        <!-- Add mousewheel plugin (this is optional) -->
        {!!Html::script('js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js')!!}
        <!-- Add fancyBox main JS and CSS files -->
        {!!Html::script('js/fancybox/source/jquery.fancybox.js?v=2.1.5"')!!}
        {!!Html::style('js/fancybox/source/jquery.fancybox.css?v=2.1.5')!!}
{{--        {!!Html::script('js/jsdiff/diff.min.js')!!}--}}
        {!!Html::script('js/clipboard.min.js')!!}

        {!!Html::script(Asset::v('/js/main.js'))!!}
        {!!Html::script(Asset::v('/js/functions.js'))!!}
        {!!Html::script(Asset::v('/js/bl.js'))!!}

    @yield('styles')
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    {{--<script type='text/javascript'>
        (function (d, t) {
            var bh = d.createElement(t), s = d.getElementsByTagName(t)[0];
            bh.type = 'text/javascript';
            bh.src = 'https://www.bugherd.com/sidebarv2.js?apikey=1qxwxwtzbfhqfsdbhfxumw';
            s.parentNode.insertBefore(bh, s);
        })(document, 'script');
    </script>--}}

    <link rel="shortcut icon" href="{!! asset('favicon.ico')  !!} ">
</head>
<body class="home">
    <script>window.inlineManualOptions = { language: 'en'};</script>
    <script>!function () {
            var e = document.createElement("script"), t = document.getElementsByTagName("script")[0];
            e.async = 1, e.src = "https://inlinemanual.com/embed/player.1c7905f80a315c6bbe0bae5a64527dd9.js", e.charset = "UTF-8", t.parentNode.insertBefore(e, t)
        }();
    </script>
    @include('partials.ga')
    @include('partials.inline-manual-tracking')
    <div class="bg-home" data-mobile="{{ (!$homedata['home_main_block']->background_mobile)? '/images/bg-home-header3.jpg':Config::get('app.homeImages').$homedata['home_main_block']->background_mobile }}"
         style="background-image:
                 url('{{(!$homedata['home_main_block']->background)? '/images/bg-home-header3.jpg':
                        Config::get('app.homeImages'). $homedata['home_main_block']->background }}');">
        <script>
            window.fbAsyncInit = function() {
                FB.init({
                    appId      : '848687605263767',
                    xfbml      : true,
                    version    : 'v2.7'
                });
            };

            (function(d, s, id){
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>



        @yield('content')



        @include('partials.footer')



        <!-- Scripts -->
        @yield('scripts')
        {!! Captcha::script() !!}
        {!!Html::script('/vendor/unisharp/laravel-ckeditor/ckeditor.js')!!}
        {!!Html::script('/vendor/unisharp/laravel-ckeditor/adapters/jquery.js')!!}
        {!!Html::script('js/bootstrap-datepicker/js/bootstrap-datepicker.min.js')!!}
        <script>
            site.initCkeditors();
        </script>
        <!-- Go to www.addthis.com/dashboard to customize your tools -->
        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-57ed3492aa5fe8f7"></script>
        {{--<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-51ffb956064f1f1f"></script>--}}
    </div>
    @include('partials.feedback')
</body>
</html>
