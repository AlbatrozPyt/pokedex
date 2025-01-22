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
        // Definindo validação de erros
        $validator = Validator::make(
            // Dados que serão validados
            $request->all(), 
            // Regras de validação
            [
                'name' => 'required|alpha',
                'lastname' => 'required|alpha',
                'birthdate' => 'required|date',
                'city' => 'required|alpha',
                'username' => 'required|unique:trainers|alpha_num',
                'password' => 'required|alpha_num',
            ],
            // Mensagens de erro, customizadas
            [
                'required' => 'Não foi possível realizar seu cadastro na Pokédex devido à falta de informações',
                'alpha' => 'Não foi possível realizar seu cadastro na Pokédex devido a informações conflitantes de tipos de dados',
                'alpha_num' => 'Não foi possível realizar seu cadastro na Pokédex devido a informações conflitantes de tipos de dados',
                'unique' => 'Não foi possível realizar seu cadastro na Pokédex devido ao seu cadastro já existir, prossiga para o login na sua Pokédex'
                ]
        );

        // Se a validação falhar
        if ($validator->fails()) {
            $errors = $validator->messages()->toArray();
            $formatResponse = array_map(fn($e) => $e[0], $errors, [])[0];

            return response()->json(['message' => $formatResponse], 422);
        }

        // Registrando o treinador no banco
        Trainer::create($request->all());

        // Mensagem de sucesso
        return response()->json(['message' => 'Treinador, você foi registrado com sucesso na sua Pokédex'], 201);
    }

    public function login(Request $request) {
        // Definindo validação de erros
        $validator = Validator::make(
            $request->all(),
            // Dados que serão validados
            [
                'username' => 'required',
                'password' => 'required',
            ],
            // Mensagens de erro, customizadas
            [
                'required' => 'Treinador, faltam dados para podermos autenticar você na sua Pokédex'
            ]
        );

        // Se a validação falhar
        if ($validator->fails()) {
            $errors = $validator->messages()->toArray();
            $formatResponse = array_map(fn($e) => $e[0], $errors, [])[0];

            return response()->json(['message' => $formatResponse], 422);
        }

        // Buscando o treinador no banco de dados
        $trainer = Trainer::where('username', $request->username)
            ->where('password', hash('sha256', $request->password))
            ->first();

        // Se o treinador não for encontrado
        if (is_null($trainer)) {
            return response()->json(['message' => 'Treinador, parece que seus dados estão incorretos, confira e tente novamente'], 401);
        }

        // Gerando o token de autorização para o treiandor
        $token = Trainer::generateAccessToken($trainer);
        
        // Retorna o token como resposta
        return response()->json(['token' => $token]);
    }

    public function logout(Request $request) {
        // Pegando o cabeçalho de autorização quando for do tipo Bearer Token
        $token = $request->bearerToken();

        // Buscando o token e apagando do banco de dados
        AccessToken::where('token', $token)->delete();

        // Retornando mensagem de sucesso
        return response()->json(['message' => 'Você saiu da sua Pokédex com sucesso']);
    }
}
