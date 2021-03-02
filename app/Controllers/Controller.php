<?php

use App\Models\User;

class Controller{

    protected $error = '';

    public function __construct()
    {

    }

    // Подключаем файлы вида
    public function view($view, $data = []) {
        if (file_exists('../app/Views/' . $view . '.php')) {
            require_once '../app/Views/' . $view . '.php';
        } else {
            die('Не найден шаблон');
        }
    }
}