<?php

namespace Model;

class ApptServices extends ActiveRecord {
    protected static $table = 'apptservices';
    protected static $columnsDB = ['id', 'appointID', 'serviceId']; 

    public $id;
    public $appointID;
    public $serviceId;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->appointID = $args['appointID'] ?? '';
        $this->serviceId = $args['serviceId'] ?? '';
    }
}