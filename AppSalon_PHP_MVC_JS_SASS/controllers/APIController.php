<?php

namespace Controllers;

use Model\Appointment;
use Model\ApptServices;
use Model\Services;

class APIController {
    public static function index () {
        $services = Services::all();
        echo json_encode($services);
    }

    public static function save() {

        // Stores the appointment and returns the id
        $appointment  = new Appointment($_POST);
        $result = $appointment->save();

        $id = $result['id']; 

        // Stores the appointment and service
        $servicesId = explode(",", $_POST['services']);
        foreach($servicesId as $serviceId) {
            $args = [
                'appointID' => $id,
                'serviceId' => $serviceId
            ];
            $apptService =  new ApptServices($args);
            $apptService->save();
        }

        echo json_encode(['result' => $result]);
    }

    public static function delete () {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $appointment = Appointment::find($id);
            $appointment->delete();
            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }
}