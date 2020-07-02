<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Question;
use App\Answer;
use Illuminate\Http\Request;
use App\Glossary\QuestionType;


class QuestionController extends Controller
{
    protected $type;
    private $rule = [
        // 'name'=>'required|max:30',
    ];

    public function __construct(){
    }
    public function index()
    {
        $questionType = (QuestionType::getAll());
        return view('admin.question.list', [
            'questionType' => $questionType
        ]);
    }
    public function getItems(){
        $filter = isset($_GET['filter'])?$_GET['filter']:array();

        $data = array();
        $data['filter'] = $filter;
        $items = Question::getItems($data['filter']);
        return $items;
    }

    public function getAnswers(){
        $question_id = request()->input('id');
        return Answer::getAnswers($question_id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('admin.question.create', [
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = request()->post();
        $data['description'] = strval($data['description']);
        $data['notes'] = strval($data['notes']);
        $answers = $data['answers'];
        if($data['id']){
            $result = Question::find($data['id']);
            $result->update($data);
        }else{
            $result = Question::create($data);
        }
        $result->answers = $this->saveAnswers($result->id,$answers);
        return ['status'=>1,'data'=>$result];
    }

    private function saveAnswers($question_id,$answers){
        if(!$question_id) return [];
        $result = [];
        $orignal = Answer::getAnswers($question_id);
        $orignalId = array_map(function($e){return $e->id;},$orignal);
        $new = array_filter($answers,function($e){return !isset($e['id']);});
        $update = array_filter($answers,function($e){return isset($e['id']);});
        $updateId = array_map(function($e){return $e['id'];},$update);
        sort($orignalId);
        sort($updateId);
        $deleteId = array_diff($orignalId,$updateId);
        if(count($deleteId) > 0){
            Answer::destroy($deleteId);
        }
        if(count($update)){
            foreach($update as $data){
                $model = Answer::find($data['id']);
                $model->update($data);
                $result[] = $model;
            }
        }
        if(count($new)){
            foreach($new as $data){
                $data['question_id'] = $question_id;
                $result[] = Answer::create($data);
            }
        }
        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $result = Question::destroy($id);
        return ['status'=>$result];
    }
}
