<?php

class ErrorController extends Controller {

    public function __construct(){
        parent::__construct();
    }

    public function index(){

        $this->view('errors/404', ['title' => 'Такой страницы не существует.']);
    }
}