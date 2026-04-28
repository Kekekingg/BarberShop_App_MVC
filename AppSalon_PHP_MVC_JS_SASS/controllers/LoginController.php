<?php

namespace Controllers;

use Model\User;
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

        $user = new User($_POST);

        //Empty Alerts
        $alerts = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $user->synchronize($_POST);
            $alerts = $user->validateNewAcc();

        }

        $router->render('auth/create-account', [
            'user' => $user,
            'alerts' => $alerts
        ]);
    }
}