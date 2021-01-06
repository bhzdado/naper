CKEDITOR.replace('question', {
    filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
    filebrowserUploadMethod: 'form',
    height: 100,
    extraAllowedContent: 'img',
    enterMode: Number(2),
    toolbarGroups: [
        {name: 'document', groups: ['mode', 'document', 'doctools']},
        {name: 'clipboard', groups: ['clipboard', 'undo']},
        {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']},
        {name: 'forms', groups: ['forms']},
        {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']},
        {name: 'links', groups: ['links']},
        {name: 'insert', groups: ['insert']},
        {name: 'styles', groups: ['styles']},
        {name: 'colors', groups: ['colors']},
        {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
        {name: 'tools', groups: ['tools']},
        {name: 'others', groups: ['others']},
        {name: 'about', groups: ['about']}
    ],
    toolbar: [
        {name: 'document', groups: ['mode', 'document', 'doctools'], items: ['Source', '-', '-']},
        {name: 'clipboard', groups: ['clipboard', 'undo'], items: ['Cut', 'Copy', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']},
        {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing'], items: ['-', '-', 'Scayt']},
        {name: 'forms', groups: ['forms'], items: ['']},
        {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']},
        {name: 'links', groups: ['links'], items: ['Link', 'Unlink', 'Anchor']},
        {name: 'insert', groups: ['insert'], items: ['Image', 'Table', 'SpecialChar']},
        {name: 'styles', groups: ['styles'], items: ['Styles', 'Format', 'Font', 'FontSize']},
        {name: 'colors', groups: ['colors'], items: ['TextColor', 'BGColor']},
        {name: 'basicstyles', groups: ['basicstyles', 'cleanup'], items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-']},
        {name: 'tools', groups: ['tools'], items: ['Maximize']},
        {name: 'others', groups: ['others'], items: ['-']},
        {name: 'about', groups: ['about']}
    ],
});

var total_answers = 0;
var total_answers_show = 0;

$(document).on('keyup', function (e) {
    var keyCode = e.keyCode || e.which;

    if (keyCode === 113) {
        add_answer();
    }
});

$(document).ready(function () {
    $('#buttonAdd').click(function () {
        add_answer();
    });

    $('#weight').focus();
    
    if($('#total_answers').val() > 0){
        $('#tableAnswer').show();
        total_answers = $('#total_answers').val();
    }

});

function submitForm(){
    $('#questionData').val(CKEDITOR.instances['question'].getData());
}

function remove_answer_line(obj) {
    var num = $(obj).parent().attr('id').replace('remove-', '');
    $('#tr_line-' + num).remove();
    $('#tr_answer-' + num).remove();
    total_answers_show--;
    
    $('#total_answers_show').val(total_answers_show);
    if(total_answers_show == 0){
        $('#tableAnswer').hide();
    }
}

function clone_answer_line(answer) {
    $('#tableAnswer').show();
    var clone = $("#tableAnswerClone > tbody").clone();
    var replace = str_replace_all(clone.html(), "xxx", total_answers);
    var item = parseInt(total_answers, 10) + 1;
    replace = str_replace_all(replace, "yyy", item);
    clone.html(replace);
  
    $("#tableAnswer > tbody").append(clone.html());

    $('#option-' + total_answers).val(answer);
    $('#answer-' + total_answers).val(total_answers);
    $('#text-abbreviated-' + total_answers).html(answer);

    total_answers++;
    $('#total_answers').val(total_answers);
    total_answers_show++;
    $('#total_answers_show').val(total_answers_show);
}

function str_replace_all(string, str_find, str_replace) {
    try {
        return string.replace(new RegExp(str_find, "gi"), str_replace);
    } catch (ex) {
        return string;
    }
}

function add_answer() {
    $('#addAnswer').dialog({
        modal: false,
        resizable: false,
        draggable: false,
        width: "70%",
        height: 400,
        hide: 'slide',
        show: 'slide',
        closeOnEscape: true,
        autoOpen: true,
        position: {
            my: "center",
            at: "center",
            of: window
        },
        buttons: {
            "Confirmar": function () {
                clone_answer_line(CKEDITOR.instances['answer'].getData());
                $('#answer').remove();
                $(this).dialog("close");
            },
            Cancel: function () {
                $('#answer').remove();
                $(this).dialog("close");
            }
        },
        open: function (event, ui) {
            $('#addAnswer').html('<textarea class="ckeditor form-control" name="answer" id="answer"></textarea>');

            CKEDITOR.replace('answer', {
                //filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
                //filebrowserUploadMethod: 'form',
                height: 100,
                toolbarGroups: [
                    {name: 'document', groups: ['mode', 'document', 'doctools']},
                    {name: 'clipboard', groups: ['clipboard', 'undo']},
                    {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']},
                    {name: 'forms', groups: ['forms']},
                    {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']},
                    {name: 'links', groups: ['links']},
                    {name: 'insert', groups: ['insert']},
                    {name: 'styles', groups: ['styles']},
                    {name: 'colors', groups: ['colors']},
                    {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                    {name: 'tools', groups: ['tools']},
                    {name: 'others', groups: ['others']},
                    {name: 'about', groups: ['about']}
                ],
                toolbar: [
                    {name: 'document', groups: ['mode', 'document', 'doctools'], items: ['Source', '-', '-']},
                    {name: 'clipboard', groups: ['clipboard', 'undo'], items: ['Cut', 'Copy', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']},
                    {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing'], items: ['-', '-', 'Scayt']},
                    {name: 'forms', groups: ['forms'], items: ['']},
                    {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']},
                    {name: 'links', groups: ['links'], items: ['Link', 'Unlink', 'Anchor']},
                    {name: 'insert', groups: ['insert'], items: ['Image', 'Table', 'SpecialChar']},
                    {name: 'styles', groups: ['styles'], items: ['Styles', 'Format', 'Font', 'FontSize']},
                    {name: 'colors', groups: ['colors'], items: ['TextColor', 'BGColor']},
                    {name: 'basicstyles', groups: ['basicstyles', 'cleanup'], items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-']},
                    {name: 'tools', groups: ['tools'], items: ['Maximize']},
                    {name: 'others', groups: ['others'], items: ['-']},
                    {name: 'about', groups: ['about']}
                ],
                uiColor: '#9AB8F3'
            });

            $('.ui-dialog').css('z-index', "99");
        },
        close: function (event, ui) {

        }
    });
}