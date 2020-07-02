<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use View;

class Controller extends BaseController
{
    public function execute(Request $request){

        $user = Auth::user();
        View::share ( 'currentUser', $user );

    	//khoi tao controller hoac view qua URL
    	if($request->input('controller')){
    		$controller = $this->get_controller($request->input('controller'));
    		$task = $request->input('task','index');
            $id = $request->input('id');
            return $controller->$task($request);
    	}
    
    	if($request->input('view')){
    		$controller = $this->get_controller($request->input('view'));
    		$id = $request->input('id');
    		$layout = $request->input('layout','index');

    		if($id){
    			return $controller->$layout($id);
    		}else{
    			return $controller->$layout();
    		}
    		
    	}
    
    	return $this->index();
	}
	
    private function get_controller($controller_str){
    	$controller = __NAMESPACE__ . '\\' .$controller_str.'Controller';
    	$controller = new $controller();
    	return $controller;
    }
    
    public function index(){
        return view('admin');
    }
}
