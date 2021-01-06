$(document).ready(function () {
  $('.btn-success').click(function(){
    apiClient.post('menu/store', $("#form_menu_create").serializeArray(), function (response) {
        if (response.success === true) {

        }
    });
  });

  if('#btn-edit').click(function(){
    apiClient.post('menu/store', $("#form_menu_create").serializeArray(), function (response) {
        if (response.success === true) {

        }
    });
});
