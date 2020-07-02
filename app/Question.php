<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';
    protected $primaryKey = 'id';
    //
    protected $fillable = array('title','description','notes','type');

    public static function getItems($filter=[]){
        $items = Question::all()->toArray();
        $ids = array_map(function($e){return $e['id'];},$items);
        if($ids){
            $answers = \App\Answer::whereIn('question_id',$ids)->get()->toArray();
        }
        foreach($items as &$item){
            $item['answers'] = array_filter($answers,function($e) use ($item) {return $e['question_id'] ==  $item['id'];});
        }
        return $items;
    }

    public function answers(){
        return $this->hasMany('App\Answer','question_id','id')->select(['id','content','point']);
    }

}
