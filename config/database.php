<?php

function getDatabaseConfig(): array
{
    return [
        "database" => [
            "test" => [
                "url" => "mysql:host=127.0.0.1;port=3306;dbname=younifirst",
                "username" => "root",
                "password" => ""
            ],
            "prod" => [
                "url" => "mysql:host=127.0.0.1;port=3306;dbname=younifirst",
                "username" => "root",
                "password" => ""
            ]
        ]
    ];
}