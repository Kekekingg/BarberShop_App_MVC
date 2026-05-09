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
}