<?php

use App\Models\Table;

class TableController extends Controller {

    public function __construct(){
        parent::__construct();
    }

    /**
     * Страница отображения содержимого таблицы
     * @param $tableName
     * @throws Exception
     */
    public function show($tableName){
        $table = new Table($tableName);

        if(!\App\Lib\Database::isTable($tableName)){
            error404();
        }

        $rowsList = $table->getList();
        $columns = $table->getColumns();

        $data = [
            'title' => 'Таблица ' . $tableName,
            'table' => $tableName,
            'columns' => $columns,
            'rowsList' => $rowsList,
        ];
        $this->view('table/edit', $data);
    }

    /**
     * Добавляем новую запись в таблицу
     * @param $tableName
     */
    public function store($tableName){
        $table = new Table($tableName);
        $table->createRow($_POST);
        redirect('/table/show/' . $tableName);
    }
}