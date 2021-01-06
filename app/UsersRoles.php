<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersRoles extends Model {

    protected $fillable = [
        'role_id', 'user_id',
    ];
    public $timestamps = false;

    public function role() {
        return $this->belongsTo(Role::class, "role_id", "user_id");
    }
}
