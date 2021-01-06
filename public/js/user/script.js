$(document).ready(function () {
    $('#cep').blur(function () {
        $('#address_user').html('');
        $('#address').val('');
        $('#neighborhood').val('');
        $('#city_name').html('');
        $('#city_id').val('');
        $('#state_id').html('');
        $('#state_name').html('');

        if ($(this).val().length > 8) {
            apiClient.get("city/searchCep", {cep: $(this).val()}, function (response) {
                $('#address_user').html(response.data.address);
                $('#address').val(response.data.address);
                $('#neighborhood').val(response.data.neighborhood);
                $('#city_name').val(response.data.name);
                $('#city_id').val(response.data.city_id);
                $('#state_id').val(response.data.state_id);
                $('#state_name').val(response.data.state);
            });
        } else if ($(this).val().length > 0) {
            $(this).val('');
            app.message("Informação inválida", "O Cep informado não é válido.", "error");
        }
    });
});