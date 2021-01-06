/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

(function ($) {

    var ApiClient = function (options) {
        this.options = $.extend(
                {
                    DEBUG: false,
                },
                options
                );

        this.testProp = 'testProp!';
    };

    ApiClient.prototype.validateToken = function (__call_back) {
        var accessToken = "Bearer " + $.cookie(APP_NAME + '-Token');

        $.ajax({
            url: API_URL + "validateToken",
            type: "GET",
            dataType: 'json',
            enctype: 'multipart/form-data',
            data: [],
            beforeSend: function (xhr, settings) {
                xhr.setRequestHeader("Authorization", accessToken);
                settings.data += "&userId=" + $.cookie(APP_NAME + '-UserId');
            },
            success: function (response) {
                app.loading('close');
                if (response.success) {
                    __call_back({success: true});
                } else {
                    __call_back({success: false});
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                app.loading('close');
                __call_back({success: false});
            },
        });
    }

    ApiClient.prototype.post = function (service, data, __call_back) {
        this._send('POST', service, data, __call_back);
    }

    ApiClient.prototype.get = function (service, data, __call_back) {
        this._send('GET', service, data, __call_back);
    }

    ApiClient.prototype.patch = function (service, data, __call_back) {
        this._send('PATCH', service, data, __call_back);
    }

    ApiClient.prototype.delete = function (service, data, __call_back) {
        this._send('DELETE', service, data, __call_back);
    }

    ApiClient.prototype._send = function (type, service, data, _callback) {
        var self = this;
        app.loading('Processando sua solicitação...');

        var accessToken = "";
        if ($.cookie(APP_NAME + '-Token') != '' && service != 'login') {
            var accessToken = "Bearer " + $.cookie(APP_NAME + '-Token');
        }

        var call_back = null;

        if (_callback && typeof (_callback) == "function") {
            call_back = _callback;
        } else if (data && typeof (data) == "function") {
            call_back = data;
        }

        $.ajax({
            url: API_URL + service,
            type: type,
            dataType: 'json',
            enctype: 'multipart/form-data',
            data: data,
            beforeSend: function (xhr, settings) {
                if (accessToken) {
                    xhr.setRequestHeader("Authorization", accessToken);
                }
                var formData = new FormData();
                formData.append('userId', $.cookie(APP_NAME + '-UserId'));

                if (settings.data) {
                    settings.data += "&userId=" + $.cookie(APP_NAME + '-UserId');
                } else {
                    settings.data = formData;
                }
            },
            success: function (response) {
                if (response.success === true) {
                    if (response.message) {
                        app.message("SUCESSO", response.message);
                    }

                    if (response.location !== undefined && response.location !== '') {
                        var type = response.location.split("::")[0];
                        switch (type) {
                            case 'Route':
                                openRoute(response.location.split("::")[1]);
                                break;
                            case 'Menu':
                                openRoute(response.location.split("::")[1]);
                                break;
                            case 'Grid':
                                openRoute(response.location.split("::")[1]);
                                break;
                            case 'Function':
                                eval(response.location.split("::")[1] + "()");
                                break;
                        }
                    }
                    app.loading('close');
                    if (call_back) {
                        call_back(response);
                    }
                } else {
                    if (response.code == 401) {
                        $.MessageBox({
                            buttonDone: "OK",
                            buttonFail: "Cancel",
                            message: "A sua autenticação expirou. Por favor autentique-se novamente.",
                            input: {
                                email: {
                                    type: "text",
                                    label: "E-mail:",
                                },
                                password: {
                                    type: "password",
                                    label: "Senha:",
                                }
                            },
                            filterDone: function (data) {
                                apiClient.post('login', data, function (response) {
                                    $.cookie(APP_NAME + '-Token', response.data.accessToken);
                                    $.cookie(APP_NAME + '-UserId', response.data.user.id);

                                    loadRoute($.cookie(APP_NAME + '-ra'));
                                });
                            }
                        });
                    } else {
                        var title = response.message;
                        var message = '';

                        $.each(response.data, function (i, value) {
                            message += value + "<br>";
                        });

                        $.each(response.errors, function (i, value) {
                            message += value + "<br>";
                        });

                        app.message(title, message, "error");
                    }
                    app.loading('close');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var responseText = jQuery.parseJSON(jqXHR.responseText);
                var message = '';

                if (responseText.data) {
                    $.each(responseText.data, function (i, value) {
                        message += value + "<br>";
                    });
                    app.message(jqXHR.status + ": " + responseText.message, message, "error");
                } else if (responseText.exception) {
                    app.message(jqXHR.status, responseText.message + "<br>" + responseText.exception, "error");
                } else {
                    app.message(jqXHR.status, responseText.message, "error");
                }

                app.loading('close');

                if (call_back !== null) {
                    call_back(jQuery.parseJSON(jqXHR.responseText).message);
                } else {
                    return jqXHR;
                }
            },
        });
    }

    window.apiClient = new ApiClient();
})(jQuery);

(function ($) {

    var App = function (options) {

    }
    /*
     App.prototype.message = function (title, message) {
     $.MessageBox({
     width: '500px',
     message: message,
     customClass: "custom_messagebox_error",
     title: title
     });
     }
     */

    App.prototype.message = function (title, message, type) {
        var type = (type !== undefined) ? type : 'success';
        var customClass = "custom_messagebox_success";

        switch (type) {
            case 'error':
                customClass = "custom_messagebox_error";
                break;
        }

        $.MessageBox({
            width: '500px',
            message: message,
            customClass: customClass,
            title: title
        });

        if (type == "success") {
            setTimeout(function () {
                $('.messagebox_button_done').click();
            }, 6000);
        }
    }

    App.prototype.loading = function (acao) {
        var _acao = (acao !== undefined) ? acao : null;

        if (_acao == 'close') {
            $('body').loading('stop');
        } else {
            var mensagem = 'Aguarde.';
            if (acao != '') {
                mensagem += "<br>" + acao;
            } else {
                mensagem += "..";
            }

            $('body').loading({
                stoppable: false,
                message: mensagem,
                zIndex: 999999999999,
                theme: 'dark',

            });

            $('.loading-overlay').css('z-index', '999999999999');
        }
    }

    App.prototype.logout = function () {
        apiClient.get('logout', function () {
            window.location = URL + 'login';
        });
    }

    App.prototype.loadHtml = function (route, _callback) {
        var self = app;
        app.loading('Enviando requisição...');

        call_back = null;
        if (_callback && typeof (_callback) == "function") {
            call_back = _callback;
        }

        $.ajax({
            url: APP_URL + route + "?userId=" + $.cookie(APP_NAME + '-UserId'),
            type: 'GET',
            dataType: 'html',
            data: [],
            enctype: 'multipart/form-data',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            beforeSend: function (xhr, settings) {
            },
            success: function (response) {
                if (call_back !== null) {
                    call_back(response);
                }

                self.loading('close');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#area_conteudo').html(jqXHR.responseText);
                self.loading('close');
            },
        });
    }

    App.prototype.openRoute = function (route, _callback) {
        var self = app;
        app.loading('Enviando requisição...');

        call_back = null;
        if (_callback && typeof (_callback) == "function") {
            call_back = _callback;
        }

        $.ajax({
            url: APP_URL + route,
            type: 'POST',
            dataType: 'html',
            enctype: 'multipart/form-data',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            beforeSend: function (xhr, settings) {
                var formData = new FormData();
                formData.append('userId', $.cookie(APP_NAME + '-UserId'));

                if (settings.data) {
                    settings.data += "&userId=" + $.cookie(APP_NAME + '-UserId');
                } else {
                    settings.data = formData;
                }
            },
            success: function (response) {
                $.cookie(APP_NAME + '-ra', "Route::" + route);
                $('#area_content').html(response);
                $('.breadcrumb-content').html($('#widget_breadcrumb').html());
                $('#widget_breadcrumb').hide();
                $('#title').html($('#widget_title').html());
                $('#widget_title').hide();

                updateButtons();

                //$('.ckeditor').ckeditor();


                $.each($('.autocomplete'), function () {
                    var _self = $(this);
                    var model = $(this).attr('id');
                    var img = "<img id='img-autocomplete' src='img/loading.gif' style='width: 80px; position: relative; left: -50px; top: -41px; display:none;'>";
                    var input = "<input type='hidden' name='" + model + "_id' id='" + model + "_id' value=''>";

                    $(this).parent().append(img + input);

                    $(this).autocomplete({
                        ajaxSettings: {
                            beforeSend: function (xhr, settings) {
                                $('#img-autocomplete').show();
                                xhr.setRequestHeader("Authorization", "Bearer " + $.cookie(APP_NAME + '-Token'));
                            }
                        },
                        serviceUrl: API_URL + model + "?userId=" + $.cookie(APP_NAME + '-UserId'),
                        transformResult: function (response) {
                            if (response !== undefined) {
                                var dataResponse = jQuery.parseJSON(response);
                                $('#img-autocomplete').hide();
                                return {
                                    suggestions: $.map(dataResponse.data, function (dataItem) {
                                        return {id: dataItem.id, value: dataItem.value, data: dataItem.data};
                                    })
                                };
                            }
                        },
                        onSelect: function (suggestion) {
                            //console.log('You selected: ' + suggestion.value + ', ' + suggestion.data);
                            $('#' + model + '_id').val(suggestion.id);
                            if (suggestion.id <= 0) {
                                _self.val('');
                            }
                            $('#img-autocomplete').hide();
                        }
                    });

                    $(this).blur(function () {
                        if ($('#' + model + '_id').val() <= 0) {
                            _self.val('');
                        }
                        $('#img-autocomplete').hide();
                    });

                });

                if (call_back !== null) {
                    call_back();
                }

                self.loading('close');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#area_content').html(jqXHR.responseText);
                self.loading('close');
            },
        });
    }

    window.app = new App();
})(jQuery);

function is_function(func) {
    return typeof window[func] !== 'undefined' && $.isFunction(window[func]);
}

function toHome() {
    $.cookie(APP_NAME + '-ra', '');
    window.location = APP_URL.slice(0, -1);
}

function loadRoute(data) {
    var type = data.split("::")[0];
    switch (type) {
        case 'Route':
            openRoute(data.split("::")[1]);
            break;
        case 'Function':
            eval(data.split("::")[1] + "()");
            break;
        default:
            openRoute(data);
            break;
    }
}

function openRoute(route) {
    route = route.replace(APP_URL, '').replace(API_URL, '');
    app.openRoute(route);
    $(this).parent().parent().parent().addClass('active open');
    $('.mobile-sticky-body-overlay').click();
}

function updateButtons() {
    $('.btn-save-form').click(function () {
        var route = $('#route').val();
        var method = $('#method').val();

        if (is_function("submitForm")) {
            submitForm();
        }

        switch (method) {
            case 'post':
                apiClient.post(route, $(this).parent().parent().serializeArray());
                break;
            case 'patch':
                apiClient.patch(route, $(this).parent().parent().serializeArray());
                break;
        }

    });

    $('.btn_search_grid').click(function () {
        var route = $(this).attr('route');
        window.location.href = '#';
        app.openRoute(route, {filters: $('.btn_search_grid').closest('form').serializeArray()});
    });

    $('#form-grid-filters').submit(function (event) {
        event.stopPropagation();
        event.stopImmediatePropagation();
        return false;
    });

    $('#form-grid-filters').find('input').keyup(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            $('.btn_search_grid').click();
        }
    });

    $('button').click(function (event) {
        event.preventDefault();
    });
    Ladda.bind('.ladda-button');
    Ladda.bind('.ladda-button', {timeout: 2000});

    $('.mask-cpf').mask('999.999.999-99');
    $('.mask-cnpj').mask('99.999.999/9999-99');
    $('.mask-cep').mask('99.999-999');
    $('.mask-date').mask('99/99/9999');
    $('.mask-telephone').mask('(99) 9999-9999');
    $('.mask-cellphone').mask('(99) 99999-9999');
}

function view(route, id) {
    openRoute(route + "/show/" + id);
}

function edit(route, id) {
    openRoute(route + "/edit/" + id);
}

function remove(route, id) {
    $.MessageBox({
        buttonDone: "Sim",
        buttonFail: "Não",
        message: "Deseja realmente continuar com a remoção deste item?"
    }).done(function () {
        apiClient.delete(route + "/destroy/" + id, function (response) {
            if (response.success === true) {
                if (table !== undefined) {
                    table.ajax.reload(null, true);
                }
            }
        });
    }).fail(function () {
        app.loading('close');
    });
}


