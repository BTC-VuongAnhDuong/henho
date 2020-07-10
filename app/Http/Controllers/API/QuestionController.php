<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Question;
use Illuminate\Support\Facades\Auth;
use App\UserAnswer;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if(!$user) return [];

        $questions = Question::getItems();
        $answers = UserAnswer::where('user_id',$user->id)->select(['question_id','answer_id'])->get()->keyBy('question_id')->toArray();
        foreach($questions as &$question){
            $question['answer'] = isset($answers[$question['id']]) ? $answers[$question['id']]['answer_id'] : false;
        }
        return $questions;
    }

    public function answer(){
        $user = Auth::user();
        $result = ['status'=>0];
        $question_id = request()->input('question_id');
        $answer_id = request()->input('answer_id');
        DB::beginTransaction();
        try{
            //delete old answer
            $oldAnswer = UserAnswer::where('question_id',$question_id)->where('answer_id',$answer_id)->where('user_id',$user->id)->delete();
            $data = [
                'user_id' => $user->id,
                'question_id' => $question_id,
                'answer_id' => $answer_id,
            ];
            UserAnswer::create($data);

            if(request()->input('last_question')){
                if($user->state == \App\Glossary\UserState::UNAVAILABLE['value']){
                    $user->state = \App\Glossary\UserState::PENDING['value'];
                    $user->save();
                }
            }
            $result['status'] = 1;
            DB::commit();
        }catch (\Exception $e) {
            DB::rollback();
            $result['message'] = $e->getMessage();
        }
        
        return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
