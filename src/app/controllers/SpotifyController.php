<?php

use Phalcon\Mvc\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Stream;

class SpotifyController extends Controller
{
    public function indexAction()
    {
        
        // try {
           
        // } catch (ClientException $e) {


        //     $email = $this->session->info['email'];
        //     $data = Users::find(

        //         [
        //             'conditions' => 'email=:email:',
        //             'bind' => [
        //                 'email' => $email,
        //             ]

        //         ]
        //     );
        //     $token = $this->eventManager->fire('spotify:refreshToken', $this, $data);
        //     echo $token;
        //     $data[0]->token = $token;
        //     $this->session->set("login", $data[0]->token);
        // }
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
        // die;
        $access = $response['access_token'];
        $this->session->set("login", $access);
        $this->session->set("uid", $clientId);
        $this->session->set("secret", $clientSecret);
        $email = $this->session->info['email'];
        // echo "<pre>";
        //  print_r($info);
        // die;

        $data = Users::find(

            [
                'conditions' => 'email=:email:',
                'bind' => [
                    'email' => $email,
                ]

            ]
        );
        //  echo "<pre>";
        //  print_r($data[0]);
        // die;
        if ($data) {
            $data[0]->token = $response['access_token'];
            $data[0]->refresh = $response['refresh_token'];
            $data[0]->update();
            // header('location:http://localhost:8080/setting');
        }


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
        try {
            $response = $client->get('https://api.spotify.com/v1/search?access_token=' . $token . '&q=' . $search . '&type=' . $type . '');
        } catch (ClientException $e) {


            $email = $this->session->info['email'];
            $data = Users::find(

                [
                    'conditions' => 'email=:email:',
                    'bind' => [
                        'email' => $email,
                    ]

                ]
            );
            $token = $this->eventManager->fire('spotify:refreshToken', $this, $data);
            echo $token;
            $data[0]->token = $token;
            $this->session->set("login", $data[0]->token);
        }
        $body = $response->getBody();
        $bod = json_decode($body, true);
        // Implicitly cast the body to a string and echo it
        // echo "<pre>";
        // print_r($bod['tracks']);
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
        $token = ($this->session->get('login'));
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

    public function addAction()
    {

        $uri = $this->request->getpost('uri');
        // echo $uri;
        // die;
        $token = ($this->session->get('login'));
        $playid = $this->request->getpost('play');
        $url = "https://api.spotify.com/";
        $client = new Client(
            [
                'base_uri' => $url,
                'headers' => ['Authorization' => 'Bearer ' . $token]
            ]
        );
        $response = $client->request('POST', "/v1/playlists/" . $playid . "/tracks?uris=" . $uri);
        echo "Track added successfully";
        die;
    }

    public function addhelperAction()
    {

        // $clients = new Client();
        // $response = $clients->get('https://api.spotify.com/v1/me?access_token=' . $access . '');
        // $bodyy = $response->getBody();
        // $bodd = json_decode($bodyy, true);
        // // Implicitly cast the body to a string and echo it
        // echo "<pre>";
        // $id = $bodd['id'];
        // $this->session->set("id", $id);

        // echo ($this->session->get('login'));
        $uri = $this->request->get('uri');
        $access = ($this->session->get('login'));
        $id = ($this->session->get('id'));
        $clientt = new Client();
        $response = $clientt->get('https://api.spotify.com/v1/users/' . $id . '/playlists?access_token=' . $access . '');
        $play = $response->getBody();
        $play = json_decode($play, true);
        $this->view->play = $play;
        $this->view->uri = $uri;
        // echo "<pre>";
        // print_r($play);
        // die;
    }
}
