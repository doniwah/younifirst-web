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
                "url" => "pgsql:host=db.gkffayfknznoctegzqhr.supabase.co;port=5432;dbname=postgres;sslmode=require",
                "username" => "postgres",
                "password" => "Zgje84p6t3UTeKJm"
            ]
        ]
    ];
}
