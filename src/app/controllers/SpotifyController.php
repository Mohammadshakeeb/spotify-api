<?php

use Phalcon\Mvc\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Stream;

class SpotifyController extends Controller
{
    public function indexAction()
    {
    }

    public function homeAction()
    {
        // echo "hii";
        
        $code = $_GET['code'];
        // $url='https://accounts.spotify.com/api/token';
        // echo $code;

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

        $query = ["grant_type" => 'authorization_code', 'code' => $code, 'redirect_uri' => 'http://localhost:8080/spotify/home'];
        $response = $client->request('POST', '/api/token', ['form_params' => $query]);

        $response =  $response->getBody();
        $response = json_decode($response, true);
        // echo "<pre>";
        // print_r($response);
        $access = $response['access_token'];
        $this->session->set("login", $access);
        $this->session->set("uid", $clientId);
        $this->session->set("secret", $clientSecret);
    

        // $token = ($this->session->get('login'));
        $clients = new Client();
        $response = $clients->get('https://api.spotify.com/v1/me?access_token=' . $access . '');
        $bodyy = $response->getBody();
        $bodd = json_decode($bodyy, true);
        // Implicitly cast the body to a string and echo it
        echo "<pre>";
        $id = $bodd['id'];
        $this->session->set("id", $id);

        // echo ($this->session->get('login'));
        $clientt = new Client();
        $response = $clientt->get('https://api.spotify.com/v1/users/' . $id . '/playlists?access_token=' . $access . '');
        $play = $response->getBody();
        $play = json_decode($play, true);
        $this->view->play = $play;
        // Implicitly cast the body to a string and echo it
        // echo "<pre>";
        // print_r($play);
        // die;

    }

    public function searchAction()
    {

        //   print_r($this->request->getpost());
        //   die;
        $search = $this->request->getpost('search');
        // die($search);
        $type = array_slice($this->request->getpost(), 1);
        $type = implode(",", $type);
        // print_r($type);
        // die;

        $token = ($this->session->get('login'));
        // echo $token;
        // die;
        $client = new Client();
        $response = $client->get('https://api.spotify.com/v1/search?access_token=' . $token . '&q=' . $search . '&type=' . $type . '');
        $body = $response->getBody();
        $bod = json_decode($body, true);
        // Implicitly cast the body to a string and echo it
        echo "<pre>";
        // print_r($bod);
        // die;
        $this->view->bod = $bod['tracks'];
        $this->view->artist = $bod['artists'];
        $this->view->playlist = $bod['playlists']['items'];

        // $artist=$bod['artists'];

        // foreach ($bod['tracks'] as $k => $v) {
        //     foreach ($v as $f => $s) {
        //         // print_r($v['items'][2]);
        //         print_r($s);
        //         // $this->view->s=$s;
        //     }
        // }
        // die;
    }
    public function createplaylistAction()
    {

        // die;


        $id = ($this->session->get('id'));
        $url = "https://api.spotify.com/";
        // $id = $this->session->get('uid');
        // echo $id;
        // die;
        $val = $this->request->getpost();
        // print_r($val);

        $client = new Client(

            [
                'base_uri' => $url,
                'headers' => ['Authorization' => 'Bearer ' . $token]

            ]
        );
        $args = [
            'name' => $val['name'],
            'description' => $val['description'],
            'public' => 'false'
        ];
        $response = $client->request('POST', '/v1/users/' . $id . '/playlists', ['body' => json_encode($args)]);
        $response =  $response->getBody();
        $response = json_decode($response, true);
        echo "<pre>";
        $playid = ($response['id']);
        $this->session->set("playid", $playid);
    }
    public function detailsAction()
    {

        $id = $this->request->get('id');
        $token = ($this->session->get('login'));
        // echo $id;


        $client = new Client();
        $response = $client->get('https://api.spotify.com/v1/playlists/' . $id . '/tracks?access_token=' . $token . '');
        $body = $response->getBody();
        $detail = json_decode($body, true);
        // Implicitly cast the body to a string and echo it
        $this->view->detail = $detail;
    }
}
