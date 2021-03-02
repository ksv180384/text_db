<?php
session_start();

require_once __DIR__ . '/../app/Config/config.php';
require_once __DIR__ . '/../app/Controllers/Controller.php';
require_once __DIR__ . '/../app/Controllers/ErrorController.php';
require_once __DIR__ . '/../app/helper.php';

require_once __DIR__ . '/../vendor/autoload.php';

$init = new App\Lib\Router();