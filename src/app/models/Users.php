<?php

use Phalcon\Mvc\Model;

class Users extends Model
{
  
    public $name;
    public $email;
    public $password;
    public $token;
    public $refresh;

}
