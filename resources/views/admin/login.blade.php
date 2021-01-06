<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <head>
  <title><?= env('APP_COMPANY_NAME'); ?> :: Autenticação</title>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="">
  <title>Autenticação :: <?= env('APP_COMPANY_NAME'); ?></title>
  <!-- GOOGLE FONTS -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet" />
  <link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />
  <!-- PLUGINS CSS STYLE -->
  <link rel="stylesheet" href="{{asset('back-assets/plugins/nprogress/nprogress.css')}}" />
  <!-- SLEEK CSS -->
  <link id="sleek-css" rel="stylesheet" href="{{asset('back-assets/css/sleek.css')}}" />
  <!-- FAVICON -->
  <link rel="shortcut icon" href="{{asset('back-assets/img/favicon.png')}}" />

  <link rel="stylesheet" href="{{asset('css/login.css')}}" />
  <link rel="stylesheet" href="{{asset('css/messageBox.min.css')}}" />

  <!--
    HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
  -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <script src="{{asset('back-assets/plugins/nprogress/nprogress.js')}}"></script>

  <script>
      var APP_NAME = "{{ config('app.name') }}";
      var WEB_URL = "{{ config('app.web_url') }}";
      var APP_URL = "{{ config('app.url') }}";
      var API_URL = "{{ config('app.url_api') }}";
      var target = "{{ $target }}";
  </script>
</head>

</head>
  <body class="" id="body">
      <div class="container d-flex flex-column justify-content-between vh-100">
      <div class="row justify-content-center mt-5">
        <div class="col-xl-5 col-lg-6 col-md-10">
          <div class="card">
            <div class="card-header bg-primary" style="background: transparent!important;">
              <div class="app-brand" style="background: transparent!important;">
                <a href="/index.html">
                  <svg class="brand-icon" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid" width="30" height="33"
                    viewBox="0 0 30 33">
                    <g fill="none" fill-rule="evenodd">

                    </g>
                  </svg>

                  <span class="brand-name"><img src="{{asset('img/logo.png')}}"></span>
                </a>
              </div>
            </div>
            <div class="card-body p-5">
              @if ($errors)
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors as $error)
                      <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div><br />
              @endif

              <form id="loginform" class="form-vertical" action="{{ route('web.authenticate') }}">
                {{ csrf_field() }}
                <div class="row">
                  <div class="form-group col-md-12 mb-4">
                    <input type="email" class="form-control input-lg" name="email" id="email" aria-describedby="emailHelp" placeholder="E-mail">
                  </div>
                  <div class="form-group col-md-12 ">
                    <input type="password" class="form-control input-lg" name="password" id="password" placeholder="Senha">
                  </div>
                  <div class="col-md-12">
                    <div class="d-flex my-2 justify-content-between">
                      <div class="d-inline-block mr-3">
                        <label class="control control-checkbox">Lembre-se de mim
                          <input type="checkbox" />
                          <div class="control-indicator"></div>
                        </label>

                      </div>
                      <p><a class="text-blue" href="#">Esquceu a senha?</a></p>
                    </div>
                    <button type="button" class="btn btn-lg btn-primary btn-block mb-4" id="btn-acessar">Acessar</button>
                    <!--
                    <p>Don't have an account yet ?
                      <a class="text-blue" href="sign-up.html">Sign Up</a>
                    </p>
                  -->
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

            <script src="{{asset('back-assets/plugins/jquery/jquery.min.js')}}"></script>
            <script src="{{asset('js/jquery.cookie.js')}}"></script>
            <script src="{{asset('js/jquery.loading.min.js')}}"></script>
            <script src="{{asset('js/messagebox.js')}}"></script>
            <script src="{{asset('js/general.js')}}"></script>
            <script src="{{asset('js/matrix.login.js')}}"></script>
</body>
</html>
