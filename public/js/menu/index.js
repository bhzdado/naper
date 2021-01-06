var dialog = null;
var editor = null;

jQuery(document).ready(function () {
    /* =============== DEMO =============== */
    // menu items
    /*
    
     */

    apiClient.get('menu', function (response) {
        var arrayjson = response.data;


        // icon picker options
        var iconPickerOptions = {searchText: "Buscar...", labelHeader: "{0}/{1}"};
        // sortable list options
        var sortableListOptions = {
            placeholderCss: {'background-color': "#cccccc"}
        };

        editor = new MenuEditor('myEditor', {listOptions: sortableListOptions, iconPicker: iconPickerOptions});
        editor.setForm($('#frmEdit'));
        editor.setUpdateButton($('#btnUpdate'));

        $('#btnOutput').on('click', function () {
            save();
        });

        $("#btnUpdate").click(function () {
            editor.update();
            $('#dialog-add').hide();
        });

        $('#btnAdd').click(function () {
            editor.add();
            $('#dialog-add').hide();
            $('.btnEditMenu').click(function () {
                $('#btnUpdate').show();
                $('#btnAdd').hide();
                $('#dialog-add').show();
            });
        });
        /* ====================================== */

        /** PAGE ELEMENTS **/
        $('[data-toggle="tooltip"]').tooltip();


        editor.setData(arrayjson);
        $('#dialog-add').hide();

        $('.btnEditMenu').click(function () {
            $('#btnUpdate').show();
            $('#btnAdd').hide();
            $('#dialog-add').show();
        });

        $("#btnCancel").click(function () {
            $('#dialog-add').hide();
        });
    });

});

function add() {
    $('#btnUpdate').hide();
    $('#btnAdd').show();
    $('#dialog-add').show();
}

function save() {
    apiClient.post('menu/store', {data: editor.getString()}, function (response) {
        console.log(response);
    });
}