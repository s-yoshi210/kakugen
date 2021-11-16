<?php

namespace App\Http\Controllers\Api;

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
            'format' => 'json',
            'exintro' => 1,
            'explaintext' => 1
        ];

        $url = $endPoint . "?" . http_build_query($params);

        $client = new Client();

        $response = $client->request('GET', $url);
        $person = $response->getBody();
        $person = json_decode($person, true);
        $person = array_column($person['query']['pages'], 'extract');

        if (empty($person[0])) {
            $person = '該当する人物に関する情報はありません';
        } else {
            $person = $person[0];
        }

        return $person;
    }
}
