<?php

namespace App\Lib;

use ErrorController;

class Router {
    protected $currentController = '';
    protected $currentMethod = '';
    protected $params = [];

    public function __construct(){
        $url = $this->getUrl();

        $controller  = !empty($url[0]) ? array_shift($url) : 'Index';
        $method  = !empty($url[0]) ? array_shift($url) : 'index';

        // Задяем оставшиеся параметры в массиве url
        $this->params = $url ? array_values($url) : [];

        // Проверяем первый параметр url (контроллер)
        if(file_exists('../app/Controllers/' . ucwords($controller) . 'Controller' . '.php')){
            // Если файл существует, то задаем контроллер из полученного url
            $this->currentController = ucwords($controller) . 'Controller';
            // Удаляем нулевой индекс массива
        }else{
            $this->currentController = 'ErrorController';
        }

        // Подключаем файл с нужным контроллером
        require_once '../app/Controllers/'. $this->currentController . '.php';

        // Создаем объект контроллера
        $this->currentController = new $this->currentController;

        // Проверяем втрой параметр url (метод объекта)
        if(isset($method)){
            // Проверяем сущетвует ли в заданном объекте полученный метод
            if(method_exists($this->currentController, $method)){
                $this->currentMethod = $method;
                // Удаляем первый индекс массива

            }else{
                $this->currentController = new ErrorController;
                $this->currentMethod = 'index';
            }
        }

        // Вызываем метод контроллера с заданными параметрами
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl(){
        if(isset($_GET['path'])){
            $url = rtrim($_GET['path'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL); // Удаляем все символы, кроме букв, цифр
            $url = explode('/', $url);
            return $url;
        }
    }
}
