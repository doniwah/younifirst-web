<?php

namespace App {

    function header(string $value)
    {
        echo $value;
    }
}

namespace App\Service {

    function setcookie(string $name, string $value)
    {
        echo "$name: $value";
    }
}