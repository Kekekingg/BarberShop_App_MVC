<?php

namespace Model;

class Services extends ActiveRecord {
    // Data Base
    protected static $table = 'services';
    protected static $columnsDB = ['id', 'servicename', 'price'];

    public $id;
    public $servicename;
    public $price;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->servicename = $args['servicename'] ?? '';
        $this->price = $args['price'] ?? '';
    }

    public function validate() {
        if (!$this->servicename) {
            self::$alerts['error'][] = "The Service Name is Require";
        }
        if (!$this->price) {
            self::$alerts['error'][] = "The Service Price is Require";
        }
        if (!is_numeric($this->price)) {
            self::$alerts['error'][] = "Price is invalid";
        }

        return self::$alerts;
    }
}