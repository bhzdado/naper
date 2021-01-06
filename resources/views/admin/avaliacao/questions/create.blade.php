<div id='widget_breadcrumb'>@include('admin.layouts.includes.breadcrumb')</div>

<div id='widget_title'>
    @IF (isset($action) && $action == 'show')
    Questão
    @ELSE 
    @if (isset($question))
    Edição de Questão
    @else 
    Cadastro de Questão
    @endif
    @ENDIF
</div>
<div class="row" style="margin:10px;">
    <div class="col-lg-12">
        <form class="form-horizontal" name="form_question_create" id="form_question_create">
            {{ csrf_field() }}
            @if (isset($question))
            <input type="hidden" id='route' value='question/update/{{ $question->id }}'>
            <input type="hidden" id='method' value='patch'>
            @else
            <input type="hidden" id='route' value='question/store/'>
            <input type="hidden" id='method' value='post'>
            @endif
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md-3 mb-3">
                        <label>ID: </label>
                        @if (isset($question))
                        <input type="text" class="form-control" id="validationServer01" value="{{ str_pad( $question->id , 8, "0", STR_PAD_LEFT) }}"  disabled="disabled">
                        @else
                        <input type="text" class="form-control" id="validationServer01" value="########"  disabled="disabled">
                        @endif
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label>Grupo:</label>
                        <input type="text" id="weight" name="weight" class="form-control" style="" value="{{ (isset($question)) ? $question->group : '' }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Peso:</label>
                        <input type="text" id="weight" name="weight" class="form-control" style="" value="{{ (isset($question)) ? $question->weight : '1' }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 mb-3">
                        <label>Questão:</label>
                        <textarea class="ckeditor form-control" name="question" id="question">{{ (isset($question)) ? $question->question : '' }}</textarea>
                        <textarea style="display: none;" class="form-control" name="questionData" id="questionData">{{ (isset($question)) ? $question->question : '' }}</textarea>
                    </div>
                </div>
                <div class="form-row" style="margin-top: 20px;">
                    <div class="col-md-12 mb-3">
                        <label style="font-weight: bold;">Respostas:</label>
                        @IF (!isset($action) || $action != 'show')
                        <img id="buttonAdd" src="{{asset('img/plus.png')}}" border="0" style="cursor: pointer;">
                        <span class="dica">clique no ícone ou pressione a tecla "F2" para adicionar</span>
                        @ENDIF
                        <table style="width: 100%; margin-top: 20px; display: none;" id="tableAnswer">
                            <thead>
                                <tr>
                                    <td style="text-align: center; background: #F8F8F8; width: 25%;" colspan="2">Resposta Correta</td>
                                    <td style="padding-right: 10px; background: #F8F8F8;" colspan="2"></td>
                                </tr>
                            </thead>
                            <tbody>
                                @php ($total_answers = 0)
                                @foreach ($answers as $answer)
                                @php ($checked = "")
                                @if($answer->id == $question->answer_id)
                                @php ($checked = "checked=checked")
                                @endif

                                <tr id="tr_line-{{ $total_answers }}">
                                    <td colspan="4" style="height: 10px;"> <hr></td>
                                </tr>
                                <tr id="tr_answer-{{ $total_answers }}">
                                    <td style=" background: #F8F8F8; text-align: center;">
                                        {{ $total_answers + 1 }})
                                    </td>
                                    <td style=" background: #F8F8F8; text-align: left; border-right: 1px solid black; ">
                                        <span style="">
                                            <input type="radio" name="answer" id="answer-{{ $total_answers }}" {{ $checked }} value="{{ $total_answers }}"> 
                                        </span> 
                                    </td>
                                    <td style=" background: #F8F8F8; padding: 15px;">
                                        <textarea class="ckeditor form-control" name="option-{{ $total_answers }}" id="option-{{ $total_answers }}" style="display: none; margin-top: 10px; padding-left: 5px;">
                                            {{ $answer->option }}
                                        </textarea>
                                        <span id="text-abbreviated-{{ $total_answers }}">{{ $answer->option }}</span>
                                    </td>
                                    <td style=" background: #F8F8F8; text-align: center;">
                                        @IF (!isset($action) || $action != 'show')
                                        <div style="cursor: pointer;" id="remove-{{ $total_answers }}" class='remove'>
                                            <img src="{{asset('img/minus.png')}}" border="0"  onclick="remove_answer_line(this);">
                                        </div>
                                        @ENDIF
                                    </td>
                                </tr>
                                @php ($total_answers++)
                                @endforeach
                            </tbody>
                        </table>
                        <input type="hidden" id="total_answers" name="total_answers" value="{{ $total_answers }}">
                        <input type="hidden" id="total_answers_show" value="{{ $total_answers }}">
                    </div>
                </div>
            </div>

            <div class="form-actions" style='margin-top:15px;'>
                @IF (!isset($action) || $action != 'show')
                <input type="button" value="Salvar" class="btn btn-success btn-save-form">
                @ENDIF
                <input type="button" value="Voltar" class="btn btn-success"  onclick="openRoute('avaliacao/question');">
            </div>
        </form>
    </div>
</div>
<table style="width: 100%; margin-top: 20px; display:none;" id="tableAnswerClone">
    <tbody>
        <tr id="tr_line-xxx">
            <td colspan="4" style="height: 10px;"> <hr></td>
        </tr>
        <tr id="tr_answer-xxx">
            <td style=" background: #F8F8F8; text-align: center;">
                yyy)
            </td>
            <td style=" background: #F8F8F8; text-align: left; border-right: 1px solid black; ">
                <span style=""><input type="radio" name="answer" id="answer-xxx" style=""> </span> 
            </td>
            <td style=" background: #F8F8F8; padding: 15px;">
                <textarea class="ckeditor form-control" name="option-xxx" id="option-xxx" style="display: none; margin-top: 10px; padding-left: 5px;"></textarea>
                <span id="text-abbreviated-xxx"></span>
            </td>
            <td style=" background: #F8F8F8; text-align: center;">
                <div style="cursor: pointer;" id="remove-xxx" class='remove'>
                    <img src="{{asset('img/minus.png')}}" border="0"  onclick="remove_answer_line(this);">
                </div>
            </td>
        </tr>
    </tbody>
</table>

<div id="addAnswer" style=""></div>


<script src="{{asset('js/avaliacao/questions.js')}}"></script> 
@IF (isset($action) && $action == 'show')
<script>
    $(document).ready(function () {
        $('input:not([type=button])').attr('disabled', 'disabled');
        $('select').attr('disabled', 'disabled');
        CKEDITOR.instances['question'].config.readOnly = true;
    });
</script>
@ENDIF