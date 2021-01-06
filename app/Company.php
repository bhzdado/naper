<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model {

    protected $fillable = [
        'company_name',
        'cnpj',
        'responsible',
        'email',
        'fantasy_name',
        'active',
        'cep',
        'address',
        'number',
        'complement',
        'neighborhood',
        'city_id',
        'telephone',
        'state_registration',
        'municipal_registration',
        'logo'
    ];

    public function users() {
        return $this->hasMany(User::class);
    }

    public function city() {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function state() {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }
}
