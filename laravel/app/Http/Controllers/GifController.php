<?php

namespace App\Http\Controllers;

use App\Interfaces\HttpServiceInterface;
use App\Http\Controllers\Controller;
use App\Interfaces\GifValidatorInterface;
use App\Models\Favourite;
use Illuminate\Http\Request;

class GifController extends Controller
{
    protected $httpService;
    protected $validator;

    public function __construct(HttpServiceInterface $httpService, GifValidatorInterface $validator)
    {
        $this->httpService = $httpService;
        $this->validator = $validator;
    }

    public function getGifById($id)
    {        
        $url = env('GIPHY_BASE_URL')."{$id}?api_key=".env('GIPHY_API_KEY');            

        return $this->httpService->get($url);
    }

    public function getGifs(Request $request)
    {
        $validation = $this->validator->validateGetGifs($request);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        }

        $limit = $request->input('limit', 10);
        $offset = $request->input('offset', 0);
        $query = $request->input('query', '');
                
        $url = env('GIPHY_BASE_URL')."search/?q={$query}&offset={$offset}&limit={$limit}&api_key=".env('GIPHY_API_KEY');

        return $this->httpService->get($url);
    }

    public function createFavourite(Request $request)
    {
        $validation = $this->validator->validateCreateFavourite($request);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 422);
        }

        Favourite::create([
            'user_id' => $request->user_id,
            'alias' => $request->alias,
            'gif_id' => $request->gif_id
        ]);
    }
}