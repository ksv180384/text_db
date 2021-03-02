<?php

namespace App\Models;

use App\Lib\Database;

class Model{
    /**
     * @var Database
     */
    protected $db;
    protected $table = null;

    public function __construct(){
        $this->db = Database::getInstance();
    }

    /**
     * Получаем все колонки таблицы
     * @return array
     * @throws \Exception
     */
    public function getColumns() {
        if(empty($this->table)){
            throw new \Exception( 'Неверная таблица в модели ' . get_class($this));
        }
        return $this->db->getColumns($this->table);
    }

}