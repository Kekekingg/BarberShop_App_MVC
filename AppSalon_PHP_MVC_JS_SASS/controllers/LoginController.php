<?php

namespace Controllers;

use Classes\Email;
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

            // Check that the alerts are empty
            if(empty($alerts)) {
                // Check that the user is not registered
                $result = $user->userExist();

                if($result->num_rows) {
                    $alerts = User::getAlerts();
                } else {
                    // Hashing password
                    $user->hashPassword();

                    // Generate a uniq token
                    $user->createToken();

                    // Send Email
                    $email = new Email($user->name, $user->email, $user->token);

                    $email->sendConfirmation();
                    debuggin($user);
                }
            }


        }

        $router->render('auth/create-account', [
            'user' => $user,
            'alerts' => $alerts
        ]);
    }
}