<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="">

    <title><?= env('APP_COMPANY_NAME'); ?></title>
    
    <link rel="stylesheet" href="{{asset('back-assets/plugins/jquery/jquery-ui-1.12.1.css')}}" />

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet" />
    <link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />
    <!-- PLUGINS CSS STYLE -->
    <link rel="stylesheet" href="{{asset('back-assets/plugins/nprogress/nprogress.css')}}" />
    <!-- No Extra plugin used -->
    <link rel="stylesheet" href="{{asset('back-assets/plugins/jvectormap/jquery-jvectormap-2.0.3.css')}}" />
    <link rel="stylesheet" href="{{asset('back-assets/plugins/daterangepicker/daterangepicker.css')}}" />
    <link rel="stylesheet" href="{{asset('back-assets/plugins/toastr/toastr.min.css')}}" />

    <link rel="stylesheet" href="{{asset('css/jquery.modal.min.css')}}" />
    
    <!-- SLEEK CSS -->
    <link id="sleek-css" rel="stylesheet" href="{{asset('back-assets/css/sleek.css')}}" />
    <!-- FAVICON -->
    <link href="{{asset('back-assets/img/favicon.png')}}" rel="shortcut icon" />
    <link rel="stylesheet" href="{{asset('css/messageBox.min.css')}}" />
    <link rel="stylesheet" href="{{asset('back-assets/css/ladda.min.css')}}" />
    
    <link rel="stylesheet" href="{{asset('back-assets/css/styles.css')}}" />
    <!--
      HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
    -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="{{asset('back-assets/plugins/nprogress/nprogress.js')}}"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        var APP_NAME = "{{ config('app.name') }}";
        var WEB_URL = "{{ config('app.web_url') }}";
        var APP_URL = "{{ config('app.url') }}";
        var API_URL = "{{ config('app.url_api') }}";

        var URL = "{{ config('app.web_url') }}";
    </script>
</head>
