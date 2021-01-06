
$(document).ready(function () {

    var login = $('#loginform');
    var recover = $('#recoverform');
    var speed = 400;

    $('#to-recover').click(function () {

        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });
    $('#to-login').click(function () {

        $("#recoverform").hide();
        $("#loginform").fadeIn();
    });

    $('#email').keypress(function (e) {
        if (e.which == 13) {
            $('#password').focus();
        }
    });

    $('#password').keypress(function (e) {
        if (e.which == 13) {
            $('#btn-acessar').click();
        }
    });

    $('#to-login').click(function () {

    });

    $('#btn-change-password').click(function () {
        if ($('#new_password').val() == '' || $('#c_password').val() == '') {
            app.message("Mudar Senha", "Favor informar a nova senha e posteriormente confirmá-la.", "error");
        } else if ($('#new_password').val() != $('#c_password').val()) {
            app.message("Mudar Senha", "A nova senha informada não confere com a o valor informado em 'Confirmar Nova Senha'.", "error");
        } else {
            $.cookie(APP_NAME + '-ra', "");
            apiClient.post('changePassword', $("#changePasswordform").serializeArray(), function (response) {
                if (response.success === true) {
                    window.location = URL + "login";
                }
            });
        }
    });

    $('#btn-acessar').click(function () {
        $.cookie(APP_NAME + '-ra', "");
        apiClient.post('login', $("#loginform").serializeArray(), function (response) {
            $.cookie(APP_NAME + '-Token', response.data.accessToken);
            $.cookie(APP_NAME + '-UserId', response.data.user.id);

            if(target){
                window.location = window.atob(target);
            } else {
                window.location = "/";
            }
        });
    });

    $('#btn-esqueceu-senha').click(function () {
        if ($("#email_verificacao").val() == '') {
            alert('ola');
        } else {
            apiClient.get('forgotPassword/' + $("#email_verificacao").val(), function (response) {
                console.log(response);
            });
        }
    });

});

$(function () {

    $('#new_password').keyup(function (e) {
        var senha = $(this).val();
        if (senha == '') {
            $('#senhaBarra').hide();
        } else {
            var fSenha = forcaSenha(senha);
            var texto = "";
            $('#senhaForca').css('width', fSenha + '%');
            $('#senhaForca').removeClass();
            $('#senhaForca').addClass('progress-bar');
            if (fSenha <= 40) {
                texto = 'Senha Fraca';
                $('#senhaForca').addClass('progress-bar-danger');
            }

            if (fSenha > 40 && fSenha <= 70) {
                texto = 'Senha Media';
                $('#senhaForca').addClass('progress-bar-media');
            }

            if (fSenha > 70 && fSenha <= 90) {
                texto = 'Senha Boa';
                $('#senhaForca').addClass('progress-bar-success');
            }

            if (fSenha > 90) {
                texto = 'Senha Muito boa';
                $('#senhaForca').addClass('progress-bar-excelent');
            }

            $('#senhaForca').text(texto);

            $('#senhaBarra').show();
        }
    });
});

function forcaSenha(senha) {
    var forca = 0;

    var regLetrasMa = /[A-Z]/;
    var regLetrasMi = /[a-z]/;
    var regNumero = /[0-9]/;
    var regEspecial = /[!@#$%&*?]/;

    var tam = false;
    var tamM = false;
    var letrasMa = false;
    var letrasMi = false;
    var numero = false;
    var especial = false;

//    console.clear();
//    console.log('senha: '+senha);

    if (senha.length >= 6)
        tam = true;
    if (senha.length >= 8)
        tamM = true;
    if (regLetrasMa.exec(senha))
        letrasMa = true;
    if (regLetrasMi.exec(senha))
        letrasMi = true;
    if (regNumero.exec(senha))
        numero = true;
    if (regEspecial.exec(senha))
        especial = true;

    if (tam)
        forca += 10;
    if (tamM)
        forca += 10;
    if (letrasMa)
        forca += 10;
    if (letrasMi)
        forca += 10;
    if (letrasMa && letrasMi)
        forca += 20;
    if (numero)
        forca += 20;
    if (especial)
        forca += 20;

//    console.log('força: '+forca);

    return forca;
}
