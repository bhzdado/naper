<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;

class QuestionGroupController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $error = $this->ValidatorMake($request, array(
            array('weight' => 'required|max:255',
                'questionData' => 'required|max:255',
                'answer' => 'required|max:255'),
            array('weight' => 'Peso',
                'questionData' => 'Questão',
                'answer' => 'Resposta Correta',
        )));

        if ($error['error']) {
            return $this->sendError("Dados Incompletos.", $error['errors'], 401);
        }

        $input = $request->except(array('_token', 'question'));

        $total_answer = $input['total_answers'];
        $input['question'] = $input['questionData'];
        $question = Question::create($input);

        $answer_id = 0;
        for ($i = 0; $i <= $total_answer; $i++) {
            if (isset($input['option-' . $i])) {
                $input['option'] = $input['option-' . $i];
                $input['question_id'] = $question->id;
                $answer = Answer::create($input);

                if ($input['answer'] == $i) {
                    $answer_id = $answer->id;
                }
            }
        }

        $question->answer_id = $answer_id;
        $question->save();

        return $this->sendResponse($question, "Questão cadastrada com sucesso.", "Grid::avaliacao/question");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    public function loadDataGrid(Request $request) {
        $f = $request->input('filters');
        $fs = explode("&", $f);
        $filters = null;

        foreach ($fs as $item) {
            $tmp = explode("=", $item);
            if ($tmp[1] != '') {
                $filters[] = array(
                    'name' => $tmp[0],
                    'value' => $tmp[1]
                );
            }
        }

        $columns = $request->input('columns');
        $order = $request->input('order');
        $start = $request->input('start');
        $length = $request->input('length');
        $page = ($start > 0) ? $start / $length : 1;
        $search = $request->input('search');

        $orderby = "question_groups.id";
        $dir = "";
        if ($order) {
            $orderColumm = $columns[$order['0']['column']]['data'];
            if ($columns[$order['0']['column']]['data'] == 'idRegister') {
                $orderColumm = "id";
            }
            $orderby = "question_groups." . $orderColumm;
            $dir = $order['0']['dir'];
        }

        if ($filters) {
            $total = \App\QuestionGroup::select("count(question_groups.*)")
                    ->orderby($orderby, $dir);
            foreach ($filters as $filter) {
                $conditions[$filter['name']] = $filter['value'];
                if ($filter['value'] != '') {
                    $total->orWhere($filter['name'], 'LIKE', '%' . $filter['value'] . '%');
                }
            }
            $total = $total->distinct()->count();
        } else {
            $total = \App\QuestionGroup::select("count(*)")
                    ->orderby($orderby, $dir);
            $total = $total->distinct()->count();
        }

        if ($filters) {
            $data = \App\QuestionGroup::select("question_groups.*", \DB::raw("LPAD(question_groups.id, 8, '0') as idRegister"))
                    ->orderby($orderby, $dir);
            foreach ($filters as $filter) {
                $conditions[$filter['name']] = $filter['value'];
                if ($filter['value'] != '') {
                    $value = $filter['value'];

                    $data->orWhere($filter['name'], 'LIKE', '%' . $value . '%');
                }
            }

            $data = $data->limit($length)->offset($start)->distinct()->get();
        } else {
            $data = \App\QuestionGroup::select("question_groups.*", \DB::raw("LPAD(question_groups.id, 8, '0') as idRegister"))
                            ->orderby($orderby, $dir)
                            ->limit($length)->offset($start)->get();
        }

        $result = array();
        foreach ($data as $dt) {
            $result[] = array(
                'idRegister' => $dt->idRegister,
                'name' => $dt->name,
                'weight' => $dt->weight,
                'action' => '<a href="#" class="" onclick="view(\'avaliacao/questionGroups\', \'' . $dt->id . '\');"><img src="img/view.png" style="width:15px"></a> '
                . '/ <a href="#" onclick="edit(\'avaliacao/questionGroups\', \'' . $dt->id . '\');" class=""><img src="img/edit.png" style="width:15px"></a> '
                . '/ <a href="#" onclick="remove(\'avaliacao/questionGroups\', \'' . $dt->id . '\');" class=""><img src="img/remove.png" style="width:15px"></a>',
            );
        }

        return $this->sendResponseData(
                        array(
                            'recordsTotal' => $total,
                            'recordsFiltered' => $total,
                            'data' => $result,
        ));
    }

}
