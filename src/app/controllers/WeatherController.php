<?php

use Phalcon\Mvc\Controller;
use GuzzleHttp\Client;

class WeatherController extends Controller
{
    public function indexAction()
    {
    }
    /**
     * function to search a perticular location
     */
    public function searchAction()
    {

        $location = $_POST['search'];
        // echo $location;

        $client = new Client();


        $response = $client->request('GET', 'http://api.weatherapi.com/v1/search.json?key=0bab7dd1bacc418689b143833220304&q=' . $location);
        $body = $response->getBody();
        $bod = json_decode($body, true);
        echo "<pre>";
        print_r($bod);
        die;
        if ($bod) {
            $this->view->location = $bod;
        } else {
            echo "Enter a proper location";
            die;
        }
    }
    /**]
     * function to display the locations
     */
    public function infoAction()
    {

        $location = $_POST['location'];
        $this->view->location = $location;

        // die;
    }
    /**
     * function to process the inforamtion about current weather conditions
     */
    public function currentAction()
    {

        $location = $_GET['loc'];
        // echo $location;
        $client = new Client();
        $response = $client->request('GET', 'http://api.weatherapi.com/v1/current.json?key=0bab7dd1bacc418689b143833220304&q=' . $location);
        $body = $response->getBody();
        $bod = json_decode($body, true);

        // echo "<pre>";
        // print_r($bod);

        // die;
        $this->view->weather = $bod;
        $this->view->location=$location;
    }
    /**
     * function to process the inforamtion about future weather conditions
     */
    public function forecastAction()
    {

        $location = $_GET['loc'];
        // echo $location;
        $client = new Client();
        $response = $client->request('GET', 'http://api.weatherapi.com/v1/current.json?key=0bab7dd1bacc418689b143833220304&q=' . $location);
        $body = $response->getBody();
        $bod = json_decode($body, true);

        // echo "<pre>";
        // print_r($bod);

        // die;
        $this->view->weather = $bod;
        $this->view->location=$location;
    }
    /**
     * function to process the inforamtion about timezone
     */
    public function timezoneAction()
    {

        $location = $_GET['loc'];
        // echo $location;
        $client = new Client();
        $response = $client->request('GET', 'http://api.weatherapi.com/v1/timezone.json?key=0bab7dd1bacc418689b143833220304&q=' . $location);
        $body = $response->getBody();
        $bod = json_decode($body, true);

        // echo "<pre>";
        // print_r($bod);

        // die;
        $this->view->weather = $bod;
        $this->view->location=$location;
    }
    /**
     * function to process the inforamtion about sports
     */
    public function sportsAction()
    {

        $location = $_GET['loc'];
        // echo $location;
        $client = new Client();
        $response = $client->request('GET', 'http://api.weatherapi.com/v1/sports.json?key=0bab7dd1bacc418689b143833220304&q=' . $location);
        $body = $response->getBody();
        $bod = json_decode($body, true);

        // echo "<pre>";
        // print_r($bod);

        // die;
        $this->view->weather = $bod;
        $this->view->location=$location;
    }
    /**
     * function to process the inforamtion about astronomy
     */
    public function astronomyAction()
    {

        $location = $_GET['loc'];
        // echo $location;
        $client = new Client();
        $response = $client->request('GET', 'http://api.weatherapi.com/v1/astronomy.json?key=0bab7dd1bacc418689b143833220304&q=' . $location);
        $body = $response->getBody();
        $bod = json_decode($body, true);

        // echo "<pre>";
        // print_r($bod);

        // die;
        $this->view->weather = $bod;
        $this->view->location=$location;
    }

    /**
     * FUNCTION TO PROCESS THE INFORMATION ABOUT AIR QUALITY
     */
    public function airqualityAction()
    {
        $p="&aqi=yes";
        $location = $_GET['loc'];
        // echo $location;
        $client = new Client();
        $response = $client->request('GET', 'http://api.weatherapi.com/v1/current.json?key=0bab7dd1bacc418689b143833220304&q=' . $location.$p);
        $body = $response->getBody();
        $bod = json_decode($body, true);

        // echo "<pre>";
        // print_r($bod);

        // die;
        $this->view->weather = $bod;
        $this->view->location=$location;
    }
}
