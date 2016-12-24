<?php

// Global Config
$GLOBALS['config'] = [
    'database' => [
        'host' => '127.0.0.1',
        'database' => 'file-hosting',
        'username' => 'root',
        'password' => 'root',
        'table' => 'users'
    ],
    'homeURL' => 'website home url'
];

// Create or update admin
$admin = [
    'name' => ucwords(trim("first last")),
    'username' => "username",
    'password' => "password",
    'admin' => "1"
];