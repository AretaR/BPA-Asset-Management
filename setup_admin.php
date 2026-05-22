<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$pdo = DB::connection()->getPdo();

$pdo->exec('ALTER TABLE users ADD COLUMN role VARCHAR DEFAULT "staff"');
$pdo->exec('ALTER TABLE users ADD COLUMN employee_id VARCHAR');
$pdo->exec('ALTER TABLE users ADD COLUMN department_id INTEGER');
$pdo->exec('ALTER TABLE users ADD COLUMN phone VARCHAR');
$pdo->exec('ALTER TABLE users ADD COLUMN position VARCHAR');

$pdo->exec("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES ('Admin', 'admin@bpa.com', '".password_hash('password', PASSWORD_BCRYPT)."', 'admin', datetime('now'), datetime('now'))");

echo "Admin user created!\n";
