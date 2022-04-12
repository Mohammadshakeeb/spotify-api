<?php

namespace App\Listeners;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Stream;
use Phalcon\Di\Injectable;

use Phalcon\Events\Event;

class notificationListeners extends Injectable
{

public function refreshToken(Event $event, $values, $data)
    {
        // echo "hii";
        // die;

       
        $refresh=$data[0]->refresh;
        // echo $refresh;
        // die;

        $clientId = "981a10afe660412ea34d54bc4bb877ad";
        $clientSecret = "3cffa8b17c7f49a8bba2c575b213c45f";
        $url = "https://accounts.spotify.com";
        // $grant="authorization_code";
        // $redirect="http://localhost:8080/spotify/home";
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . base64_encode($clientId . ":" . $clientSecret)
        ];
        $client = new Client(
            [

                'base_uri' => $url,
                'headers' => $headers
            ]
        );

        $query = ["grant_type" => 'refresh_token', 'refresh_token' => $refresh];
        $response = $client->request('POST', '/api/token', ['form_params' => $query]);
        $response =  $response->getBody();
        $response = json_decode($response, true);
        // echo "<pre>";
        // print_r($response);
        // die;
        return $response['access_token'];


    }
}