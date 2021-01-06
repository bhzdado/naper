<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

    protected $fillable = [
        'name', 'slug',
    ];
    
    protected $table = 'roles';

    public function permissions() {

        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }

    public function users2222() {
        dd('teste');
        return $this->belongsToMany(User::class, 'users_roles');
    }

    public function users() {
        return $this->hasMany('App\User', 'role_id', 'id');
    }

}
