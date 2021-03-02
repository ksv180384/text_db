<?php

namespace App\Models;

use App\Lib\Database;

class Table extends Model {

    public function __construct($tableName){
        parent::__construct();
        $this->table = $tableName;
    }

    /**
     * Получаем массив таблиц
     * @return array
     */
    public static function getTablesList() {
        $db = Database::getInstance();
        return $db->getTablesList();
    }

    /**
     * Получаем список записейв таблице
     * @return array
     */
    public function getList(){

        return $this->db->query('SELECT FROM ' . $this->table);
    }

    /**
     * Добавляем новую таблицу
     * @param array $fields
     */
    public function createTable($fields){
        $this->db->query('CREATE TABLE ' . $this->table . '(' . implode(',', $fields) . ')');
    }

    /**
     * Добавляем строку в таблицу
     * @param array $fieldsValues
     */
    public function createRow($fieldsValues){

        $fields = implode(',', array_keys($fieldsValues));
        $values = implode(',', $fieldsValues);

        $this->db->query('INSERT INTO ' . $this->table . ' (' . $fields . ') VALUES (' . $values . ')');
    }
}
