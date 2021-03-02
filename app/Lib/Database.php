<?php

namespace App\Lib;

use App\Lib\Db\DbText;

class Database {

    /**
     * @var DbText
     */
    private $db;

    private static $instances = null;

    public function __construct()
    {

    }

    public static function getInstance()
    {
        if (!isset(self::$instances)) {
            self::$instances = new static();
            self::$instances->db = new DbText();
            self::$instances->db->connect(DB_NAME);
        }

        return self::$instances;
    }

    /**
     * Получаем массив всех таблиц
     * @return mixed
     */
    public static function getTablesList() {
        return self::$instances->db->getTablesList();
    }

    /**
     * Проверяем наличие таблицы
     * @param string $table
     * @return mixed
     */
    public static function isTable($table) {
        return self::$instances->db->isTable($table);
    }

    /**
     * Запросы в БД
     * @param string $sql
     * @return array
     */
    public function query($sql) {
        return $this->db->query($sql);
    }

    /**
     * Получаем массив колонок таблицы
     * @param string $table
     * @return array
     */
    public function getColumns($table) {
        return $this->db->getColumns($table);
    }

    protected function __clone() { }

}