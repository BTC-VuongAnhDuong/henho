<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    function getUserInfo(){
        $user = Auth::user();
        return $user->toArray();
    }

    function update(){
        $user = Auth::user();
        $user->fill(request()->post());
        return [
            'status' => $user->save()
        ];
    }
}
