<?php

class Customer {
    public $customer_id;
    public $pet_name;
    public $pet_birthday;
    public $phone_number;
    public $created_by;
    public $created_at;
    public $updated_by;
    public $updated_at;

    public function __construct($customer_id, $pet_name, $pet_birthday, $phone_number, $created_by, $created_at, $updated_by, $updated_at) {
        $this->customer_id = $customer_id;
        $this->pet_name = $pet_name;
        $this->pet_birthday = $pet_birthday;
        $this->phone_number = $phone_number;
        $this->created_by = $created_by;
        $this->created_at = $created_at;
        $this->updated_by = $updated_by;
        $this->updated_at = $updated_at;
    }
}