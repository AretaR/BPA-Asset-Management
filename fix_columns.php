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
echo "Done\n";
