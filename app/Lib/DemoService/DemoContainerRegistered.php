<?php

namespace App\Lib\DemoService;

use App\Models\Photo;

class DemoContainerRegistered
{
    private $message;

    public function __construct()
    {
        $this->message = '<p>demo service container REGISTERED message</p>';
    }

    public function printMessage()
    {
        return $this->message;
    }
}
