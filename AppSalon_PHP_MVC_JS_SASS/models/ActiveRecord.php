<?php
namespace Model;
class ActiveRecord {

    // Data Base
    protected static $db;
    protected static $table = '';
    protected static $columnsDB = [];

    protected static $id; // -> ELIMINAR SI FALLA LA BD

    // Alerts and messages
    protected static $alerts = [];

    // Define the connection to the DB - includes/database.php
    public static function setDB($database) {
        self::$db = $database;
    }

    public static function setAlert($type, $message) {
        static::$alerts[$type][] = $message;
    }

    // Validation
    public static function getAlerts() {
        return static::$alerts;
    }

    public function validate() {
        static::$alerts = [];
        return static::$alerts;
    }

    // SQL query to create an in-memory object
    public static function querySQL($query) {
        // Data base query
        $result = self::$db->query($query);

        // Iterate the results
        $array = [];
        while($record = $result->fetch_assoc()) {
            $array[] = static::createObj($record);
        }

        // Free memory
        $result->free();

        // return results
        return $array;
    }

    // Create the object in memory, is the same in the DB
    protected static function createObj($record) {
        $object = new static;

        foreach($record as $key => $value ) {
            if(property_exists( $object, $key  )) {
                $object->$key = $value;
            }
        }

        return $object;
    }

    // Identificar y unir los atributos de la BD

    // Identify and join the attributes of the DB
    public function attributes() {
        $attributes = [];
        foreach(static::$columnsDB as $column) {
            if($column === 'id') continue;
            $attributes[$column] = $this->$column;
        }
        return $attributes;
    }

    // Sanitize the data before saving it on the DB
    public function sanitizeAttributes() {
        $attributes = $this->attributes();
        $sanitized = [];
        foreach($attributes as $key => $value ) {
            $sanitized[$key] = self::$db->escape_string($value);
        }
        return $sanitized;
    }

    //Synchronizes DB with objects in memory
    public function synchronize($args=[]) { 
        foreach($args as $key => $value) {
          if(property_exists($this, $key) && !is_null($value)) {
            $this->$key = $value;
          }
        }
    }

    // Records - CRUD
    public function save() {
        $result = '';
        if(!is_null($this->id)) {
            // actualizar
            $result = $this->update();
        } else {
            // Creando un nuevo registro
            $result = $this->create();
        }
        return $result;
    }

    // All the records
    public static function all() {
        $query = "SELECT * FROM " . static::$table;
        $result = self::querySQL($query);
        return $result;
    }

    // Searchs a record by ID
    public static function find($id) {
        $query = "SELECT * FROM " . static::$table  ." WHERE id = {$id}";
        $result = self::querySQL($query);
        return array_shift( $result ) ;
    }

    // Get records with a certain amount
    public static function get($limite) {
        $query = "SELECT * FROM " . static::$table . " LIMIT {$limite}";
        $result = self::querySQL($query);
        return array_shift( $result ) ;
    }

    // Create new record
    public function create() {
        // Sanitize the data
        $attributes = $this->sanitizeAttributes();

        // Insert into the DB
        $query = " INSERT INTO " . static::$table . " ( ";
        $query .= join(', ', array_keys($attributes));
        $query .= " ) VALUES (' "; 
        $query .= join("', '", array_values($attributes));
        $query .= " ') ";

        // Query result
        $result = self::$db->query($query);
        return [
           'result' =>  $result,
           'id' => self::$db->insert_id
        ];
    }

    // Update record
    public function update() {
        // Sanitize the data
        $attributes = $this->sanitizeAttributes();

        // Iterate to add each field to the database
        $values = [];
        foreach($attributes as $key => $value) {
            $values[] = "{$key}='{$value}'";
        }

        // SQL query
        $query = "UPDATE " . static::$table ." SET ";
        $query .=  join(', ', $values );
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 "; 

        // Update BD
        $result = self::$db->query($query);
        return $result;
    }

    // Delete a record by ID
    public function delete() {
        $query = "DELETE FROM "  . static::$table . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $result = self::$db->query($query);
        return $result;
    }

}