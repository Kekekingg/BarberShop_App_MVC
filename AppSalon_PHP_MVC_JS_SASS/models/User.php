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
        $this->admin = $args['admin'] ?? 0;
        $this->confirmed = $args['confirmed'] ?? 0;
        $this->token = $args['token'] ?? '';
    }

    // Validation messages for creating an account
    public function validateNewAcc() {
        if(!$this->name) {
            self::$alerts['error'][] = "The name is required";
        }

        if(!$this->last_name) {
            self::$alerts['error'][] = "The last name is required";
        }

        if(!$this->email) {
            self::$alerts['error'][] = "The Email is required";
        }
        
        if(!$this->password) {
            self::$alerts['error'][] = "Password is required";
        }

        if(strlen($this->password) < 6) {
            self::$alerts['error'][] = "The password must be at least 6 characters long";
        }
        return self::$alerts;
    }

    // Check if the user exist
    public function userExist () {
        $query = "SELECT * FROM " . self::$table . " WHERE email = '" . $this->email . "' LIMIT 1";

        $result = self::$db->query($query);

        if($result->num_rows) {
            self::$alerts['error'][] = 'User already registered';
        }

        return $result;
    }

    public function hashPassword () {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function createToken () {
        $this->token = uniqid();
    }

}