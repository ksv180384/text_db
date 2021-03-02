<?php

namespace App\Lib\Db;
class DbText
{
    /**
     * Массив доступных команд
     * @var array
     */
    private $commands = [
        'select',
        'create',
        'insert',
        'from',
        'table',
        'into',
        'values',
        'where',
    ];

    /**
     * Используемая команда
     * @var string
     */
    private $command = '';

    /**
     * Поля таблицы
     * @var string
     */
    private $fields = '';

    /**
     * Название таблицы
     * @var string
     */
    private $table = '';

    /**
     * Параметры where
     * @var string
     */
    private $where = '';

    /**
     * Значения для записи
     * @var string
     */
    private $values = '';

    /**
     * Путь к файлу с данными таблицы
     * @var string
     */
    private $path = '';

    public function __construct()
    {
    }

    /**
     * Задаем путь к файлу с данными
     * @param string $db
     * @throws \Exception
     */
    public function connect($db = 'data')
    {
        $path = __DIR__ . '/' . $db;
        if(!is_dir($path)) {
            throw new \Exception( 'База данных не найдена.');
        }
        $this->path = $path;
    }

    /**
     * Получаем данные из файла при помощи запроса SQL
     * @param $sql
     * @return array
     */
    public function query($sql){
        $this->parseSql($sql);

        $command = $this->command;

        if(!method_exists($this, $command)){
            return [];
        }
        return $this->$command();
    }

    /**
     * Получаем список всех таблиц
     * @return array
     */
    public function getTablesList(){
        $dirData = scandir($this->path);
        $files = array_filter($dirData, function($file) {
            return !is_dir($this->path . '/' . $file) && stripos($file, 'schema_') !== false;
        });
        $files = array_values($files);
        $tables = array_map(function($file){
            $posStart = strripos($file, '.');
            $posEnd = strlen($file);
            $nameTable = substr($file, 0, -($posEnd - $posStart));
            $nameTable = str_replace('schema_', '', $nameTable);

            return $nameTable;
        }, $files);

        return $tables;
    }

    /**
     * Получаем списов колонок таблицы
     * @param $table
     * @return array
     */
    public function getColumns($table){
        $fileName = $this->path . '/schema_' . $table . '.txt';
        $file = file_get_contents($fileName);
        $columns = explode(',', $file);

        return $columns;
    }

    /**
     * Проверяем наличие таблицы
     * @param $table
     * @return bool
     */
    public function isTable($table){

        return file_exists( $this->path . '/schema_' . $table . '.txt');
    }

    /**
     * Парсим SQL запрос
     * @param $sql
     * @return array
     */
    private function parseSql($sql)
    {
        $posCommand = [];
        $sql = mb_strtolower($sql);

        foreach ($this->commands as $command){
            $pos = strpos($sql, $command);
            if($pos !== false){
                $posCommand[] = [
                    'command' => $command,
                    'pos' => $pos,
                ];
            }
        }

        $this->command = $posCommand[0]['command'] ?: '';

        $commandsData = [];
        for($i = 0; $i < count($posCommand); $i++){
            $keyNextElement = $i + 1;
            if(!empty($posCommand[$keyNextElement])){
                $length = $posCommand[$keyNextElement]['pos'] - $posCommand[$i]['pos'];
            }else{
                $length = strlen($sql) - $posCommand[$i]['pos'];
            }
            $commandsData[$posCommand[$i]['command']] = trim(substr($sql, $posCommand[$i]['pos'], $length));
        }


        $this->initFrom($commandsData);
        $this->initWhere($commandsData);
        $this->initParams($commandsData[$this->command]);
        $this->initCreate($commandsData);
        $this->initInsert($commandsData);

        return $commandsData;
    }

    /**
     * Определяем $where из полученного запроса
     * @param $val
     * @return bool
     */
    private function initWhere($val)
    {
        if(empty($val['where'])){
            return true;
        }
        $val = $val['where'];

        $whereParams = str_replace('where', '', $val);
        $whereParams = str_replace(' ', '', $whereParams);
        $this->where = $whereParams;
        return true;
    }

    /**
     * Определяем $table из полученного запроса
     * @param $val
     * @return bool
     */
    private function initFrom($val)
    {
        if(empty($val['from'])){
            return true;
        }
        $val = $val['from'];

        $fromParams = str_replace('from', '', $val);
        $fromParams = str_replace(' ', '', $fromParams);
        $fromParams = trim($fromParams, '`');
        $this->table = $fromParams;
        return true;
    }

    /**
     * Определяем $fields из полученного запроса
     * @param $val
     * @return bool
     */
    private function initParams($val)
    {
        if(empty($val)){
            return true;
        }

        $params = str_replace($this->commands, '', $val);
        $params = str_replace(' ', '', $params);
        $params = trim($params, '`');
        $this->fields = $params;
        return true;
    }

    /**
     * Определяем $fields, $table из полученного запроса
     * @param $val
     * @return bool
     */
    private function initCreate($val)
    {
        if(empty($val['table'])){
            return true;
        }
        $val = $val['table'];

        $p = str_replace('table', '', $val);
        $p = str_replace(' ', '', $p);

        $posStart = strpos($p, '(');
        $posEnd = strpos($p, ')');

        $table = substr($p, 0, $posStart);
        $table = trim(str_replace(' ', '', $table), '`');

        $params = trim(substr($p, $posStart, ($posEnd - $posStart)), '(');


        $this->table = $table;
        $this->fields = $params;
        return true;
    }

    /**
     * Определяем $fields, $table, $values из полученного запроса
     * @param $val
     * @return bool
     */
    private function initInsert($val)
    {
        if(empty($val['into'])){
            return true;
        }
        $valInto = $val['into'];
        $valValues = $val['values'];

        $p = str_replace('into', '', $valInto);
        $p = str_replace(' ', '', $p);

        $posStart = strpos($p, '(');
        $posEnd = strpos($p, ')');

        $table = substr($p, 0, $posStart);
        $table = trim(str_replace(' ', '', $table), '`');

        $params = trim(substr($p, $posStart, ($posEnd - $posStart)), '(');

        $v = trim(str_replace('values', '', $valValues), '');
        $posStart = strpos($v, '(');
        $posEnd = strpos($v, ')');
        $values = trim(substr($v, $posStart, ($posEnd - $posStart)), '(');

        $this->table = $table;
        $this->fields = $params;
        $this->values = $values;
        return true;
    }

    /**
     * Получаем данные из файла. Команда SELECT SQL запроса
     * @return array
     */
    private function select()
    {
        $fileName = $this->path . '/table_' . $this->table . '.txt';
        $file = file_get_contents($fileName);
        $rows = explode("\n", $file);

        array_pop ($rows);
        $rows = array_map(function($row){
            return unserialize($row);
        }, $rows);

        return $rows;
    }

    /**
     * Создаем файлы для хранения данных и структуры таблицы. Команда CREATE SQL запроса
     * @return bool
     */
    private function create()
    {
        $params = explode(',', $this->fields);
        $params = array_map(function($p){
            return trim($p, '`');
        }, $params);
        $params = implode(',', $params);

        $fileName = $this->table . '.txt';
        $fp = fopen($this->path . '/schema_' . $fileName, 'w');

        fwrite($fp, $params);
        fclose($fp);

        fopen($this->path . '/table_' . $fileName, 'w+');

        return true;
    }

    /**
     * Добавляем записи в файл. Команда INSERT SQL запроса
     * @return bool
     */
    private function insert()
    {

        $fields = explode(',', $this->fields);
        $values = explode(',', $this->values);
        if(count($fields) != count($values)){
            return false;
        }

        for ($i = 0; $i < count($fields); $i++){
            $row[trim($fields[$i], '`')] = $values[$i];
        }

        $rowSerialize = serialize($row);

        $fileName = $this->table . '.txt';
        $fp = fopen($this->path . '/table_' . $fileName, 'a');

        fwrite($fp, $rowSerialize . PHP_EOL);
        fclose($fp);
        return true;
    }
}