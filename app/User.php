<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Glossary\UserType;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','state','type','gender','phone','gender_target','single_state',
        'height','weight','birthday','job','provincial','district'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin(){
        return $this->type == UserType::SUPER_ADMIN['value'];    
    }

    public static function getItems($filterData){
        $users = User::where(function ($query) use ($filterData) {

            foreach ($filterData AS $key => $value){
                if (isset($value) && $value){
//                    $query->where($key, $value);
                }
            }


            if (isset($filterData['group_id']) && $filterData['group_id']){
                $query->where('group_id', $filterData['group_id']);
            }
            if (isset($filterData['gender']) && $filterData['gender']){
                $query->where('gender', $filterData['gender']);
            }
            if (isset($filterData['start']) && isset($filterData['end']) && $filterData['start'] && $filterData['end']){
                $query->where('created_at', '>=', $filterData['start']);
                $query->where('created_at', '<=', $filterData['end']);
            }

            if (isset($filterData['age_from']) && isset($filterData['age_to']) && $filterData['age_to'] && $filterData['age_from']){
//                $query->where('YEAR(CURDATE()) - YEAR(birthdate)', '>=', $filterData['age_from']);
            }


            })
            ->paginate(20);

        return $users;
    }
}
