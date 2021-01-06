<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tribute extends Model {

    protected $fillable = [
        'name', 'published',
    ];

}
