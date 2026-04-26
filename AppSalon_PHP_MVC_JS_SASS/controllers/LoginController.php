<?php

namespace Controllers;

use MVC\Router;

class LoginController {
    public static function login (Router $router) {

        
        $router->render('auth/login');
    }

    public static function logout () {
        echo "From Logout";
    }

    public static function forget (Router $router) {
    
        $router->render('auth/forget-password', [

        ]);
    }

    public static function recover () {
        echo "From Recover";
    }

    public static function create (Router $router) {
        

        $router->render('auth/create-account', [

        ]);
    }
}