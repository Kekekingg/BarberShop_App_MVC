<?php

namespace Controllers;

use Classes\Email;
use Model\User;
use MVC\Router;

class LoginController {
    public static function login (Router $router) {

        $alerts = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new User($_POST);

            $alerts = $auth->validateLogin();

            if (empty($alerts)) {
                // Check if the user exist
                $user = User::where('email', $auth->email);

                if($user) {
                    // Check Password
                    if ($user->checkPasswordAndVerify($auth->password)) {
                        session_start();

                        $_SESSION['id'] = $user->id;
                        $_SESSION['name'] = "{$user->name} {$user->last_name}";
                        $_SESSION['email'] = $user->email ?? null;
                        $_SESSION['login'] = true;

                        // Redirection

                        if($user->admin === '1') {
                            $_SESSION['admin'] = $user->admin ?? null;

                            header('Location: /admin');
                        } else {
                            header('Location: /appointment');
                        }

                    }
                } else {
                    User::setAlert('error', 'User not found');
                }
            }

        }

        $alerts = User::getAlerts();

        $router->render('auth/login', [
            'alerts' => $alerts
        ]);
    }

    public static function logout () {
        echo "From Logout";
    }

    public static function forget (Router $router) {

        $alerts = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new User($_POST);

            $alerts = $auth->validateEmail();

            if(empty($alerts)) {
                $user = User::where('email', $auth->email);

                if($user && $user->confirmed === "1") {
                    // Generate a Token
                    $user->createToken();
                    $user->save();

                    // Send Email
                    $email = new Email($user->email, $user->name, $user->token);
                    $email->sendInstructions();

                    // Success Alert
                    User::setAlert('success', 'Check your email');

                } else {
                    User::setAlert('error', 'The user does not exist or is not confirmed');
                }
            }
        }

        $alerts = User::getAlerts();

        $router->render('auth/forget-password', [
            'alerts' => $alerts
        ]);
    }

    public static function recover (Router $router) {

        $router->render('auth/recover-password', [
            
        ]);
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
                    $email = new Email($user->email, $user->name, $user->token);

                    $email->sendConfirmation();

                    // Create user
                    $result = $user->save();
                    // debuggin($user);
                    if($result) {
                        header('Location: /message');
                    }

                }
            }


        }

        $router->render('auth/create-account', [
            'user' => $user,
            'alerts' => $alerts
        ]);
    }

    public static function message (Router $router) {

        $router->render('auth/message');
    }

    public static function confirm (Router $router) {

        $alerts = [];
        $token = san($_GET['token']);
        $user = User::where('token', $token);

        if(empty($user)) {
            // Show error message
            User::setAlert('error', 'Invalid Token');
        } else {
            // Show user confirmed
            $user->confirmed = "1";
            $user->token = null;
            $user->save();
            User::setAlert('success', 'Account Successfully Confirmed');
        }

        // Get alerts
        $alerts = User::getAlerts();

        // Render the layout
        $router->render('auth/confirm-account', [
            'alerts' => $alerts
        ]);
    }
}