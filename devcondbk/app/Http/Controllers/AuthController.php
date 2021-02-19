<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Suppport\Facades\Auth;

use App\Models\User;
use App\Models\Unit;

class AuthController extends Controller
{
    public function unauthorized() {
        return response()->json([
            'error' => 'Não autorizado'
        ], 401);
    }

    public function register(Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'name' =>  'required',
            'email' => 'required|email|unique:users,email', // unique:users, email(verifica se existe um usuário com o mesmo email cadastrado, caso tenha já barra o processo).
            'cpf' => 'required|digits:11|unique:users,cpf',
            'password' => 'required',
            'password_confirm' => 'required|same:password' // same(mesma) que o password digitado.
         ]);
        
         // Verifica se não ouve nenhum erro.
         if(!$validator->fails()) {
            // Pega os dados do usuário.
            $name = $request->input('name');
            $email = $request->input('email');
            $cpf = $request->input('cpf');
            $password = $request->input('password');
            
            // Gera um hash da senha.
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Cria usuário.
            $newUser = new User();
            // Preenche os dados do usuário com dados enviados.
            $newUser->name = $name;
            $newUser->email = $email;
            $newUser->cpf = $cpf;
            $newUser->password = $hash;
            $newUser->save(); // Salva usuário.


            // Depois de criar usuário loga com ele.
            $token = auth()->attempt([
                'cpf' => $cpf,
                'password' => $password
            ]);
            
            // Verifica se não foi gerado um token para aquele usuário e mostra um erro caso não tenha.
            if(!$token) {
                $array['error'] = 'Ocorrreu um erro.';
                return $array;
            }

            // Adiciona o token gerado em um array de resposta.
            $array['token'] = $token;

            // Precisa-se adicionar as informações desse usuário e as propriedades do mesmo.
            // que no caso são as unidades que estão associadas a esse usuário.
            $user = auth()->user();
            $array['user'] = $user;

            // Pega as propriedades do usuário.
            $properties = Unit::select(['id', 'name'])
            ->where('id_owner', $user['id'])
            ->get();

            // E adiciona as propriedades.
            $array['user']['properties'] = $properties;

         } else {
             $array['error'] = $validator->errors()->first();
             return $array;
         }

        return $array;
    }
}
