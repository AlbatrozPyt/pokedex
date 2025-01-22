<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    public function data(Request $request) {
        $token = $request->bearerToken();
        $session = AccessToken::where('token', $token)->first();

        return Trainer::select([
            'name',
            'lastname',
            'birthdate',
            'city',
            'username'
        ])
        ->where('id', $session->trainer_id)
        ->first();
    }
}
