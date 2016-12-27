<?php

// Global Config
$GLOBALS['config'] = [
    'database' => [
        'host' => 'db-host-name',
        'database' => 'database-name',
        'username' => 'db-username',
        'password' => 'db-password',
        'table' => 'table-name'
    ],
    'homeURL' => 'website-home-url'
];

// Create or update admin
$admin = [
    'name' => ucwords(trim("first last")),
    'username' => "username",
    'password' => "password",
    'admin' => "1"
];