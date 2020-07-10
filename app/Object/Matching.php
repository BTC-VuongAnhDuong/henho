<?php
namespace App\Object;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Glossary\UserState;
use App\Glossary\UserGender;

class Matching{
    static $height_diff = 10;
    function __construct(){
        
    }

    private function caculateQuestionPoint(){

    }

    private function compare($p1,$p2){
        if($p1['id'] == $p2['id']){
            return false;
        }
        //check gender
        if($p1['gender'] != $p2['gender_target'] || $p1['gender_target'] != $p2['gender']){
            return false;
        }
        //check height
        if($p1['gender'] == UserGender::MALE['value']){
            if($p1['height'] - $p2['height'] < Matching::$height_diff){
                return false;
            }
        }else{
            if($p2['height'] - $p1['height'] < Matching::$height_diff){
                return false;
            }
        }
        //@todo match by question

        return true;

    }

    function getMatch(){
        $unmatch = User::where('state',UserState::PENDING['value'])->where('type',\App\Glossary\UserType::CLIENT['value'])->get()->toArray();
        if(count($unmatch) == 0) return false;

        $personID = array_map(function($p){return $p['id'];},$unmatch);

        //get user answer
        $question_answer = \App\UserAnswer::whereIn('user_id',$personID)->select(['user_id','question_id','answer_id'])->get()->toArray();
        foreach($unmatch as $i=>$person){
            $unmatch[$i]['answer'] = array_filter($question_answer,function($a) use ($person){return $a['user_id'] == $person['id'];});
        }
        $match = [];
        foreach($unmatch as $person){
            foreach($unmatch as $compare){
                if($this->compare($person,$compare)){
                    $match[] = ['user_id'=>$person['id'],'match_user_id'=>$compare['id']];
                }
            }
        }
        //find duplicate
        if(count($match) > 0){
            $matched = DB::table('matching')->whereIn('user_id',array_map(function($m){return $m['user_id'];},$match))->get()->toArray();
            foreach($matched as $m){
                $m = (array)$m;
                foreach($match as $i=>$mn){
                    if($m['user_id'] == $mn['user_id'] && $m['match_user_id'] == $mn['match_user_id']){
                        unset($match[$i]);
                    }
                }
            }
            DB::table('matching')->insert($match);
        }
        // die;
        return count($match);
    }

}