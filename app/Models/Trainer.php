<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $fillable = [
        'name',
        'lastname',
        'birthdate',
        'city',
        'username',
        'password'
    ];

    public $timestamps = false;

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = hash('sha256', $value);
    }

    public static function generateAccessToken($t)
    {
        $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = base64_encode(json_encode(['id' => $t->id,  'username' => $t->username, 'timestamp' => time()]));
        $sig = base64_encode(hash_hmac('sha256', "$header.$payload", 'gojira'));
        $token = "$header.$payload.$sig";

       
        $verifyIfTokenExists = AccessToken::where('trainer_id', $t->id)->first();

        if (is_null($verifyIfTokenExists)) {
            AccessToken::create([
                'trainer_id' => $t->id,
                'token' => $token
            ]);
        } else {
            $verifyIfTokenExists->update(['token' => $token]);
        }

        return $token;
    }
}
