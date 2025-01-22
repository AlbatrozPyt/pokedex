<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\AssignOp\Pow;

class PokemonController extends Controller
{
    public function read(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['id' => 'required'],
            ['required' => 'Treinador, estão faltando dados']
        );

        if ($validator->fails()) {
            $errors = $validator->messages()->toArray();
            $formatResponse = array_map(fn($e) => $e[0], $errors, [])[0];

            return response()->json(['message' => $formatResponse], 422);
        }

        $pokemonExists = Pokemon::find($request->id);

        if (is_null($pokemonExists)) {
            if (is_null($request->name)) {
                return response()->json(['message' => 'Treinador, estão faltando dados']);
            }
        }

        Pokemon::updateOrCreate(
            ['id' => $request->id],
            [
                'id' => $request->id,
                'name' => !is_null($request->name) ? $request->name : $pokemonExists->name,
                'type' => !is_null($request->type) ? $request->type : $pokemonExists->type,
                'base' => !is_null($request->base) ? $request->base : $pokemonExists->base,
                'species' => !is_null($request->species) ? $request->species : $pokemonExists->species,
                'description' => !is_null($request->description) ? $request->description : $pokemonExists->description,
                'evolution_prev' => !is_null($request->evolution) ? $request->evolution['prev'] : $pokemonExists->prev,
                'evolution_next' => !is_null($request->evolution) ? $request->evolution['next'] : $pokemonExists->next,
                'profile' => !is_null($request->profile) ? $request->profile : $pokemonExists->profile,
                'egg' => !is_null($request->profile) ? $request->profile : $pokemonExists->egg,
                'ability' =>  !is_null($request->profile) ? $request->profile['ability'] : $pokemonExists->ability,
                'image' => !is_null($request->image) ? $request->image['hires'] : $pokemonExists->image
            ]
        );

        if (is_null($pokemonExists))
            return response()->json(['message' => 'Pokémon criado com sucesso na base de dados'], 201);

        return response()->json(['message' => 'Dados do Pokémon atualizados com sucesso']);
    }

    public function list()
    {
        $pokemon = Pokemon::all()->toArray();

        $formatResponse = array_map(fn($p) => [
            'id' => $p['id'],
            'name' => [
                'english' => $p['name']
            ],
            'image' => [
                'hires' => $p['image']
            ]
        ], $pokemon);

        return response()->json($formatResponse);
    }

    public function view(Request $request) {
        if (is_null($request->id)) {
            return response()->json(['message' => 'Treinador, este Pokémon não existe na base de dados'], 401);
        }

        $pokemonExists = Pokemon::find($request->id);

        if (is_null($pokemonExists)) {
            return response()->json(['message' => 'Treinador, faltou informar o número do Pokémon']);
        }

        return [
            'id' => $pokemonExists->id,
            'name' => [
                'english' => $pokemonExists->name
            ],
            'type' => explode('|', $pokemonExists->type),
            'base' => [
                'HP' => intval(explode('|', $pokemonExists->base)[0]),
                'Attack' => intval(explode('|', $pokemonExists->base)[1]),
                'Defense' => intval(explode('|', $pokemonExists->base)[2]),
                'Sp. Attack' => intval(explode('|', $pokemonExists->base)[3]),
                'Sp. Defense' => intval(explode('|', $pokemonExists->base)[4]),
                'Speed' => intval(explode('|', $pokemonExists->base)[5]),
            ],
            'species' => $pokemonExists->species,
            'description' => $pokemonExists->description,
            'evolution' => [
                'prev' => array_map(fn($e) => explode('-', $e), explode('|', $pokemonExists->evolution_prev)),
                'next' => array_map(fn($e) => explode('-', $e), explode('|', $pokemonExists->evolution_next)),
            ], 
            'profile' => [
                'height' => explode('|', $pokemonExists->profile)[0],
                'weight' => explode('|', $pokemonExists->profile)[1],
                'egg' => explode('|', $pokemonExists->egg),
                'ability' => array_map(fn($a) => explode('-', $a), explode('|', $pokemonExists->ability)),
                'gender' => explode('|', $pokemonExists->profile)[2],
            ],
            'image' => [
                'hires' => $pokemonExists->image
            ]
        ];
    }
}
