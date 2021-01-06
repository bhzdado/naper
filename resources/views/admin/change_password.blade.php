<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <title><?= env('APP_COMPANY_NAME'); ?> :: Autenticação</title><meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="{{asset('back-assets/css/bootstrap.min.css')}}" />
        <link rel="stylesheet" href="{{asset('back-assets/css/bootstrap-responsive.min.css')}}" />
        <link rel="stylesheet" href="{{asset('back-assets/css/matrix-login.css')}}" />
        <link rel="stylesheet" href="{{asset('back-assets/font-awesome/css/font-awesome.css')}}" />
        <link rel="stylesheet" href="{{asset('css/messageBox.min.css')}}" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

        <style>
            .password-strength {
                display: block;
                height: 12px;
                position: relative
            }

            .password-strength::after {
                content: attr(title);
                color: #fff;
                font-size: 10px;
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                transition: width 200ms ease-in-out;
                text-align: center
            }

            .progress-bar-media {
                background-color: #333;
                width: 100%
            }

            .progress-bar-danger {
                background-color: #e74c3c;
                width: 33%
            }

            .progress-bar-success {
                background-color: #f1c40f;
                width: 66%
            }

            .progress-bar-excelent {
                background-color: #2ecc71;
                width: 100%
            }

            .progress {
                border: 1px solid;
                background: transparent;
                margin-bottom: 0px;
                width: 150px;
                position: relative;
                left: 321px;
                top: 40px;
                content: attr(title);
                color: #fff;
                font-size: 10px;
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                transition: width 200ms ease-in-out;
                text-align: center
            }

            input, span { margin: 4px }
        </style>
        <script>
            var APP_NAME = "{{ config('app.name') }}";
            var WEB_URL = "{{ config('app.web_url') }}";
            var APP_URL = "{{ config('app.url') }}";
            var API_URL = "{{ config('app.url_api') }}";
            var URL = "{{ config('app.web_url') }}";
        </script>
    </head>
    <body>
        <div id="loginbox">    
            <form id="changePasswordform" class="form-vertical" action="{{ route('web.authenticate') }}">
                {{ csrf_field() }}
                <input type="hidden" name="activate_code" id="activate_code" value="{{ $activate_code }}"/>
                <input type="hidden" name="action" id="action" value="{{ (isset($action)) ? $action : "" }}"/>
                <div class="control-group normal_text"> <h3><img src="{{asset('back-assets/img/logo.png')}}" alt="Logo" /></h3></div>
                <div class="control-group">
                    <div class="controls">
                        <div id="senhaBarra" class="progress" style="display:none;">
                            <div id="senhaForca" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                            </div>
                        </div>
                        <div class="main_input_box">
                            <span class="add-on bg_lg"><i class="icon-lock"> </i></span>
                            <input type="password" placeholder="Nova Senha" name="new_password" id="new_password"/>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_ly"><i class="icon-lock"></i></span>
                            <input type="password" placeholder="Confirmar Nova Senha" name="c_password" id="c_password"/>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div style="width: 200px;float: left;">
                        A senha deve possuir no mínimo 6 caracteres e no máximo 12.
                    </div>
                    <div class="pull-right"><button type="button" class="btn btn-success" id="btn-change-password">Trocar</button></div>
                </div>
            </form>
        </div>

        <script src="{{asset('back-assets/js/jquery.min.js')}}"></script>  
        <script src="{{asset('js/jquery.cookie.js')}}"></script> 
        <script src="{{asset('js/jquery.loading.min.js')}}"></script>
        <script src="{{asset('js/messagebox.js')}}"></script> 
        <script src="{{asset('js/general.js')}}"></script> 
        <script src="{{asset('back-assets/js/matrix.login.js')}}"></script> 

    </body>

</html>
