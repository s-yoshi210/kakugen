<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PersonController extends Controller
{
    public function index(Request $request)
    {
        $endPoint = 'https://ja.wikipedia.org/w/api.php';

        $params = [
            'action' => 'query',
            'prop' => 'extracts',
            'redirects' => 1,
            'titles' => $request['person_name'],
            'format' => 'json'
        ];

        $url = $endPoint . "?" . http_build_query($params) . "&exintro&explaintext";

        $client = new Client();

        $response = $client->request('GET', $url);
        $person = $response->getBody();
        $person = json_decode($person, true);

        return $person;
    }
}
