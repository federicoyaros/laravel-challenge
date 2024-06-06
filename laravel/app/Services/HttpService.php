<?php

namespace App\Services;

use App\Interfaces\HttpServiceInterface;
use GuzzleHttp\Client;

class HttpService implements HttpServiceInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function Get($url)
    {               
        try {
            $response = $this->client->request('GET', $url);
            $data = json_decode($response->getBody()->getContents(), true);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error trying to make the HTTP request'], 500);
        }
    }
}