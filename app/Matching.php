<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Glossary\UserType;
use App\User;
use Illuminate\Support\Arr;


class Matching extends Model
{
    protected $table = 'matching';
    protected $primaryKey = 'id';

    public static function getQueryData($filterData){
        $query = Matching::select('user_id');
        if (Arr::get($filterData, 'user_id')){
            $query->where('user_id', $filterData['user_id']);
        }

        $query->groupBy('user_id');
        $query->orderBy(Arr::get($filterData, 'sort_column','user_id'),Arr::get($filterData, 'sort_direction','ASC'));
        return $query;
    }

    public static function getItems($filterData){
        $query = self::getQueryData($filterData);
        $users = $query->offset(Arr::get($filterData,'page_offset',0))
        ->limit(Arr::get($filterData,'page_limit',5))
        ->get()->toArray();
        // dump($users);die;
        $userIds = array_map(function($e){return $e['user_id'];},$users);
        //get matched user
        $matching = Matching::whereIn('user_id',$userIds)->select('user_id','match_user_id')->get()->toArray();
        $matchIds = Arr::flatten($matching);
        //fetch user info to user
        // $userIds = array_merge($matchIds,$userIds);
        $userIds = array_unique($matchIds);
        $userInfo = User::whereIn('id',$userIds)->get()->keyBy('id')->toArray();
        //fetch match user
        $result = [];
        foreach($userIds as $user_id){
            $user = $userInfo[$user_id];
            $user['match'] = [];
            $thisUserMatch = array_filter($matching,function($e) use ($user_id) {return $e['user_id'] == $user_id;});
            $thisUserMatchIds = array_map(function($e){return $e['match_user_id'];},$thisUserMatch);
            foreach($thisUserMatchIds as $id){
                $user['match'][] = $userInfo[$id];
            }
            $result[] = $user;

        }
        return $result;
    }

    public static function getTotal($filterData){
        $query = self::getQueryData($filterData);
        $users = $query->count();
        return $users;
    }
}
