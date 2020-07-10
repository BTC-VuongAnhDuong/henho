<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use App\User;
use Illuminate\Http\Request;
use App\Glossary\UserType;


class UserController extends Controller
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
        $provinceGroup = \App\Province::all()->toArray();
        return view('admin.user.list', [
            'filter' => request()->input('filter',[]),
            'items' => $this->getItems(),
            'province' => $provinceGroup
        ]);
    }
    public function getItems(){
        $filter = isset($_GET['filter'])?$_GET['filter']:array();

        $data = array();
        $data['filter'] = $filter;
        $items = User::getItems($data['filter']);
        return $items;
    }

    public function create()
    {
        $userGroup = UserGroup::all();
        $provinceGroup = ProvinceGroup::getListProvince();
        dump($provinceGroup);die;
    	return view('admin.user.create', [
            'group' => $userGroup,
            'province' => $provinceGroup
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $new_user = $request->get('data');

        $new_user['password'] = Hash::make($new_user['password']);


        $id = User::create($new_user)->id;

        $request->validate([
            'avatar' => 'sometimes|mimes:jpeg,bmp,png|max:20000',
        ]);

        $filename = null;
        if($request->hasFile('avatar')) {
            $filename = (string) time().'.'.$request->avatar->getClientOriginalExtension();
            $request->avatar->storeAs('user'.$id.'/avatar', $filename);
        }

        $user = User::find($id);
        $user['avatar'] = env('APP_URL').'/storage/app/public/user'.$id.'/avatar/'.$filename;
        $user->save();

        $favourite = $request->get('favourite');

        $plans = array();
        if (!empty($favourite)){
            foreach ($favourite AS $value){
                $plans[] = array(
                    'user_id' => $id,
                    'hobby_id' => $value
                );
            }
        }
        if (!empty($plans))DB::table('user_hobby_map')->insert( $plans );

        return redirect('/admin?view=User');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function show($id)
    {
        $user = User::find($id);
        
        // store user id in session for use update avatar
        Session::put('current_user_id', $id);

        // show the view and pass the nerd to it
        return view('admin.user.detail', ['item' => $user]);
    }

    public static function block(Request $request){
        $current = Auth::user();
        $id =  $request->input('id');
        if ($id == $current->id){
            return redirect(url('admin?view=User'))->withErrors(__('admin.CANNOT_BLOCK_YOURSELF'));
        }
        $user = User::find($id);
        if ($user->is_admin == 1 && $current->is_admin != 1){
            return redirect(url('admin?view=User'))->withErrors(__('admin.CANNOT_BLOCK_ADMINISTRATOR'));
        }

        if ($current->is_admin != 1){
            if ($user->parent_id != $current->id){
                return redirect(url('admin?view=User'))->withErrors(__('admin.CANNOT_BLOCK_THIS_USER'));
            }
        }

        $user->is_blocked = !$user->is_blocked;
        $result = $user->save();
        if ($result) {
            return redirect('admin?view=User')->with('success', [__('admin.SAVE_SUCCESS')]);
        }else{
            return redirect('admin?view=User')->withErrors(__('admin.SAVE_FAIL'));
        }

    }
    
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // console_log($id);
    	$user = User::find($id);

        $provinceGroup = \App\Province::all()->toArray();

        return view('admin.user.detail', [
            'item' => $user,
            'province' => $provinceGroup
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id =  $request->input('id');
        $user = User::find($id);
        $data = $request->get('data');

        foreach ($data as $key => $value) {
            $user->$key = $value;
        }

        $result = $user->save();
        if ($result)
        {
            // $favourite = $request->get('favourite');

            // $plans = array();
            // $plans = array();
            // if (!empty($favourite)){
            //     foreach ($favourite AS $value){
            //         $plans[] = array(
            //             'user_id' => $id,
            //             'hobby_id' => $value
            //         );
            //     }
            // }

            // DB::table('user_hobby_map')->insert( $plans );

            return redirect('admin?view=User&layout=edit&id='.$id)->with('success', [__('Save success')]);
        }else{
            return redirect('admin?view=User&layout=edit&id='.$id)->withErrors(__('Save failed'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id =  $request->input('id');
        User::destroy($id);
        // redirect to previous url after destroy
        Session::put('pre_url', URL::previous());
        console_log(Session::get('pre_url'));
        return redirect(Session::get('pre_url'));
    }

    public function resetUserPassword(Request $request) {
        $id = $request->input('id');
        $user = User::find($id);

        $new_password = str_random(10);
        $user->password = Hash::make($new_password);
        // ở đây cần gửi mật khẩu mới qua mail cho người dùng
        $user->save();
        return redirect('admin?view=User');
    }

    public function updateUserAvatar(Request $request) {
        $id = $request->get('user_id');
        $selectedUser = User::find($id);
        $file = $request->file();

        if ($file){
            foreach ($file AS $value){
                $file_name = time() . '_' . $value->getClientOriginalName();
                $file_path = 'storage/app/user'.$id.'/avatar/';
                $newFile = $value->move($file_path, $file_name);
                $url = $file_path . $file_name;
                $selectedUser->avatar = $url;
                $selectedUser->save();
            }
        }

        return redirect('admin?view=User&layout=edit&id='.$id);
    }

    public function checkVip(){
        $id = 1;
        $data = \App\User::checkVip($id);
        echo "<pre>";
        print_r($data);
        die;
    }
}
