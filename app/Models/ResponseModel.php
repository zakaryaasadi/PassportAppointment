<?php

namespace App\Models;

class ResponseModel
{
    public $message;
    public $status_code;
    public $results;


    public function __construct($status, $message, $results = [])
    {
        $this->message = $message;
        $this->status_code = $status;
        $this->results = $results;
    }

    public function toJson(){
        return json_encode($this);
    }
}
