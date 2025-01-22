<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    public $table = 'access_tokens';

    protected $fillable = [ 
        'trainer_id',
        'token',
        'created_at',
        'updated_at'
    ];
}
