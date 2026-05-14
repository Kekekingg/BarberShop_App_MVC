<?php

namespace Controllers;

use Model\AdminApptm;
use MVC\Router;

class AdminController {
    public static function index (Router $router) {

        // If there is no date, generate the server's date (today's date).
        $date = $_GET['date'] ?? date('Y-m-d');
        $dates = explode('-', $date);

        if(!checkdate($dates[1], $dates[2], $dates[0])) {
            header('Location: /404');
        }

        // Consult the database
        $consult = "SELECT appointments.id, appointments.time, CONCAT( users.name, ' ', users.last_name) as client, ";
        $consult .= " users.email, users.phone, services.servicename as service, services.price  ";
        $consult .= " FROM appointments  ";
        $consult .= " LEFT OUTER JOIN users ";
        $consult .= " ON appointments.userId=users.id  ";
        $consult .= " LEFT OUTER JOIN apptservices ";
        $consult .= " ON apptservices.appointID=appointments.id ";
        $consult .= " LEFT OUTER JOIN services ";
        $consult .= " ON services.id=apptservices.serviceId ";
        $consult .= " WHERE date = '$date' ";

        $appointments = AdminApptm::SQL($consult);

        $router->render('admin/index', [
            'name' => $_SESSION['name'] ?? '',
            'appointments' => $appointments,
            'date' => $date
        ]);
    }
}