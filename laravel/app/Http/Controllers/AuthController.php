<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\AuthValidatorInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;

class AuthController extends Controller
{
    private $client;    
    protected $validator;

    public function __construct(AuthValidatorInterface $validator)
    {
        $this->client = Client::where('password_client', 1)->first();        
        $this->validator = $validator;
    }

    public function login(Request $request)
    {
        $validation = $this->validator->validateLogin($request);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        }
        
        $params = [
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '*',
        ];

        $request->request->add($params);

        $proxy = Request::create('oauth/token', 'POST');

        return Route::dispatch($proxy);
    }

    public function register(Request $request)
    {
        $validation = $this->validator->validateRegister($request);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User registered successfully'], 201);
    }
}
