<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    //
    protected $table = 'devvn_tinhthanhpho';
    protected $primaryKey = 'matp';
    //
    protected $fillable = array('name','type');

}
