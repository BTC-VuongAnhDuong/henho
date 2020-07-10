<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\Matching;
use Illuminate\Http\Request;
use App\Glossary\UserType;


class MatchingController extends Controller
{
    protected $type;
    private $rule = [
        // 'name'=>'required|max:30',
    ];

    public function __construct(){
    }
    public function index()
    {
        // $UserType = (UserType::getAll());
        return view('admin.matching.list', [
        ]);
    }
    public function getItems(){
        $filter = isset($_GET['filter'])?$_GET['filter']:array();

        $data = array();
        $data['filter'] = $filter;
        $items = Matching::getItems($data['filter']);
        return $items;
    }

    public function refresh(){
        return ['status'=>1,'data'=>(new \App\Object\Matching())->getMatch()];
    }



}
