<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Glossary\UserType;
use App\User;
use Illuminate\Support\Arr;


class Matching extends Model
{
    protected $table = 'matching';
    protected $primaryKey = ['user_id','match_user_id'];

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
        dump($users);die;
        $userIds = array_map(function($e){return $e->user_id;},$users);
        //get matched user
        // $matched = 
        //fetch user info to user
        $userIds = array_flatten($users);
        $userIds = array_unique($userIds);
        $userInfo = User::whereIn('id',$userIds)->get()->toArray();

        return $users;
    }

    public static function getTotal($filterData){
        $query = self::getQueryData($filterData);
        $users = $query->count();
        return $users;
    }
}
