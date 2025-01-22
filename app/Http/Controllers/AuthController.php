<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|alpha',
                'lastname' => 'required|alpha',
                'birthdate' => 'required|date',
                'city' => 'required|alpha',
                'username' => 'required|unique:trainers|alpha_num',
                'password' => 'required|alpha_num',
            ],
            [
                'required' => 'Não foi possível realizar seu cadastro na Pokédex devido à falta de informações',
                'alpha' => 'Não foi possível realizar seu cadastro na Pokédex devido a informações conflitantes de tipos de dados',
                'alpha_num' => 'Não foi possível realizar seu cadastro na Pokédex devido a informações conflitantes de tipos de dados',
                'unique' => 'Não foi possível realizar seu cadastro na Pokédex devido ao seu cadastro já existir, prossiga para o login na sua Pokédex'
                ]
        );

        if ($validator->fails()) {
            $errors = $validator->messages()->toArray();
            $formatResponse = array_map(fn($e) => $e[0], $errors, [])[0];

            return response()->json(['message' => $formatResponse], 422);
        }

        Trainer::create($request->all());

        return response()->json(['message' => 'Treinador, você foi registrado com sucesso na sua Pokédex'], 201);
    }

    public function login(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required',
                'password' => 'required',
            ],
            [
                'required' => 'Treinador, faltam dados para podermos autenticar você na sua Pokédex'
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->messages()->toArray();
            $formatResponse = array_map(fn($e) => $e[0], $errors, [])[0];

            return response()->json(['message' => $formatResponse], 422);
        }

        $trainer = Trainer::where('username', $request->username)
            ->where('password', hash('sha256', $request->password))
            ->first();

        if (is_null($trainer)) {
            return response()->json(['message' => 'Treinador, parece que seus dados estão incorretos, confira e tente novamente'], 401);
        }

        $token = Trainer::generateAccessToken($trainer);
        
        return response()->json(['token' => $token]);
    }

    public function logout(Request $request) {
        $token = $request->bearerToken();
        AccessToken::where('token', $token)->delete();

        return response()->json(['message' => 'Você saiu da sua Pokédex com sucesso']);
    }
}
