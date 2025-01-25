<?php

namespace App\Exceptions;

use Exception;

class OrderNotFoundException extends Exception
{
    protected $id;

    public function __construct($message, $id)
    {
        parent::__construct($message);
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}