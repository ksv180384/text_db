<?php

use App\Models\Table;

class IndexController extends Controller {

    public function __construct(){
        parent::__construct();
    }

    /**
     * Главная страница со списком всех таблиц
     */
    public function index(){

        $tablesList = Table::getTablesList();

        $this->view('index', [
            'title' => 'Список таблиц',
            'tablesList' => $tablesList,
        ]);
    }

    /**
     * Добавляем новую таблицу для хранения данных
     */
    public function store(){

        if(empty($_POST['fields'])){
            redirect('/');
        }
        if(empty($_POST['table_name'])){
            redirect('/');
        }

        $tableName = strtolower($_POST['table_name']);
        $fields = $_POST['fields'];

        $table = new Table($tableName);
        $table->createTable($fields);
        redirect('/');
    }
}