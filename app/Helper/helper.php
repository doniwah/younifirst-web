<?php

namespace App {
    function header(string $value)
    {
        // echo agar PHPUnit bisa menangkap output
        echo $value;
    }
}

namespace App\Service {
    function setcookie(string $name, string $value)
    {
        echo "$name: $value";
    }
}
