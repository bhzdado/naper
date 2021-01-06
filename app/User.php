<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Permissions\HasPermissionsTrait;
use Illuminate\Contracts\Events\Dispatcher;
use App\Access;
use Request;

class User extends Authenticatable {

    use HasApiTokens,
        Notifiable;
    use HasPermissionsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'activation_code',
        'company_id',
        'cpf',
        'cnpj',
        'cep',
        'address',
        'number',
        'complement',
        'neighborhood',
        'city_id',
        'telephone',
        'cellphone',
        'avatar',
        'active'
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

    private function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function registerAccess() {
        return $this->accesses()->create([
                    'user_id' => $this->id,
                    'datetime_login' => date('YmdHis'),
                    'ip' => $this->get_client_ip()
        ]);
    }

    public function registerOut($id) {
        $access = Access::where(array('user_id' => $id, 'datetime_logout' => null))->first();
        if ($access) {
            $access->datetime_logout = date('YmdHis');
            $access->save();
        }
    }

    public function city() {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function state() {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function company() {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function roles222() {
        return $this->belongsToMany(Role::class, 'users_roles');
    }

    public function role222() {
        return $this->hasOne('App\Role', 'id', 'role_id');
    }

    public function role() {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function accesses() {
        return $this->hasMany(Access::class);
    }

    public function hasRole($roles) {
        $this->have_role = $this->getUserRole();
        
        // Check if the user is a root account
        if (strtolower($this->have_role->role) == 'root') {
            return true;
        }

        if (is_array($roles)) {
            foreach ($roles as $need_role) {
                if ($this->checkIfUserHasRole($need_role)) {
                    return true;
                }
            }
        } else {
            return $this->checkIfUserHasRole($roles);
        }
        return false;
    }

    private function getUserRole() {
        return $this->role()->getResults();
    }

    private function checkIfUserHasRole($need_role) {
        return (strtolower($need_role) == strtolower($this->have_role->role)) ? true : false;
    }

}
