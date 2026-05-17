<?php

namespace Controllers;

use MVC\Router;
use Model\Services;

class ServiceController {
    public static function index (Router $router) {

        isAdmin();
        $services = Services::all();

        $router->render('services/index', [
            'name' => $_SESSION['name'],
            'services' => $services
        ]);
    }

    public static function create (Router $router) {

        isAdmin();

        $servicename = new Services;
        $alerts = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicename->synchronize($_POST);

            $alerts = $servicename->validate();

            if (empty($alerts)) {
                $servicename->save();
                header('Location: /services');
            }
        }

        $router->render('services/create', [
            'name' => $_SESSION['name'],
            'servicename' => $servicename,
            'alerts' => $alerts
        ]);
    }

    public static function update (Router $router) {

        isAdmin();

        $id = $_GET['id'] ?? null;
        if (!is_numeric($id)) return;

        $servicename = Services::find($id);
        $alerts = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicename->synchronize($_POST);

            $alerts = $servicename->validate();

            if(empty($alerts)) {
                $servicename->save();

                header('Location: /services');
            }

        }

        $router->render('services/update', [
            'name' => $_SESSION['name'],
            'servicename' => $servicename,
            'alerts' => $alerts
        ]);
    }

    public static function delete (Router $router) {

        isAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $servicename = Services::find($id);
            $servicename->delete();
            header('Location: /services');
        }
    }
}