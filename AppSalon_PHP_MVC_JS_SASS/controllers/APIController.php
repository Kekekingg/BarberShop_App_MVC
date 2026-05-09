<?php

namespace Controllers;

use Model\Services;

class APIController {
    public static function index () {
        $services = Services::all();

        echo json_encode($services);
    }
}