$(document).ready(function () {
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');

    $('#image').change(function (event) {
        readURL(this);

        $('#but_upload').show();
        $('#image-user-preview').show();
    });

    $("#but_upload").click(function () {
        var fd = new FormData();
        var files = $('#image')[0].files[0];
        fd.append('image', files);

        var accessToken = "";
        if ($.cookie(APP_NAME + '-Token') != '') {
            var accessToken = "Bearer " + $.cookie(APP_NAME + '-Token');
        }

        $.ajax({
            url: API_URL + "user/saveAvatar/" + $('#id').val(),
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            beforeSend: function (xhr, settings) {
                if (accessToken) {
                    xhr.setRequestHeader("Authorization", accessToken);
                }

                status.empty();
                var percentVal = '0%';
                bar.width(percentVal);
                percent.html(percentVal);
            },
            success: function (response) {
                if (response.success === true) {
                    $(".user-image").attr("src", URL + response.data.filename);
                    app.message("SUCESSO", response.message);
                } else {
                    alert('Erro ao enviar o arquivo.');
                }
                $('#but_upload').hide();
                $('#image-user-preview').hide();
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
                $('#but_upload').hide();
                $('#image-user-preview').hide();
            },
        });
    });

    $('#btn_change_password').click(function () {
        if ($('#new_password').val() == '' || $('#c_password').val() == '') {
            app.message("Mudar Senha", "Favor informar a nova senha e posteriormente confirmá-la.", "error");
        } else if ($('#new_password').val() != $('#c_password').val()) {
            app.message("Mudar Senha", "A nova senha informada não confere com a o valor informado em 'Confirmar Nova Senha'.", "error");
        } else {
            $.cookie(APP_NAME + '-ra', "");
            apiClient.post('changePasswordProfile', $("#changePasswordform").serializeArray(), function (response) {
                if (response.success === true) {
                    window.location = URL + "login";
                }
            });
        }
    });
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#previewHolder').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}