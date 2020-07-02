<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Question;

class Answer extends Model
{
    //
    protected $table = 'answers';
    //
    protected $fillable = array('question_id','content','point');

    public static function getAnswers($question_id){
        return Answer::where('question_id',(int)$question_id)->get()->all('items');
    }
}
