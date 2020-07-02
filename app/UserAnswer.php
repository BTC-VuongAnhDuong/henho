<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    //
    protected $table = 'user_answers';
    protected $primaryKey = 'id';
    //
    protected $fillable = array('user_id','question_id','answer_id');

}
