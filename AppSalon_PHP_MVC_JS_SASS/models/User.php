<?php

namespace Model;

class User extends ActiveRecord {
    // Data Base
    protected static $table = 'users';
    protected static $columnsDB = ['id', 'name', 'last_name', 'email', 'password', 'phone', 'admin', 'confirmed', 'token'];
    public $id;
    public $name;
    public $last_name;
    public $email;
    public $password;
    public $phone;
    public $admin;
    public $confirmed;
    public $token;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->last_name = $args['last_name'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->phone = $args['phone'] ?? '';
        $this->admin = $args['admin'] ?? null;
        $this->confirmed = $args['confirmed'] ?? null;
        $this->token = $args['token'] ?? '';
    }

    // Validation messages for creating an account
    public function validateNewAcc() {
        if(!$this->name) {
            self::$alerts['error'][] = "The customer's name is required";
        }

        if(!$this->last_name) {
            self::$alerts['error'][] = "The customer's last name is required";
        }
        return self::$alerts;
    }

}