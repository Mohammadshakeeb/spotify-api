<?php

use Phalcon\Mvc\Controller;

class LoginController extends Controller
{
    public function indexAction()
    {
        //return '<h1>Hello!!!</h1>';
    }

    public function loginAction(){
    
            $data = $this->request->getpost();
            $email = $this->request->getpost('email');
            $password = $this->request->getpost('password');
           
            $data = Users::find(

                [
                    'conditions' => 'email=:email: and password= :password:',
                    'bind' => [
                        'email' => $email,
                        'password' => $password
                    ]

                ]
            );

          
            //if any row matches the id and password
            if (count($data) > 0) {

                $userdata = array(
                    'name' => $data[0]->name,
                    'email' => $data[0]->email,
                    'password' => $data[0]->password,
                );
                $this->session->info = $userdata;
                $this->session->email=$data[0]->email;
           
               
              
             
                    header('location: http://localhost:8080/spotify');
            } else {

                $this->view->message = "some errors occured while signing you up: <br>" . implode("<br>", $user->getMessages());

            }
    }

}