<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 22/11/14
 * Time: 15:03
 */

class Common {

    public static function initDBConnection($dbName) {
        $connection = mysql_connect('localhost', 'bd2', 'bd2') or die("No s'ha pogut conectar a la base de dades");
        $db = mysql_select_db($dbName, $connection) or die("No s'ha trobat la base de dades " . $dbName);
        mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $connection);
        return $connection;
    }

    public static function initPDOConnection($dbName) {
        return new PDO('mysql:host=localhost;dbname=' . $dbName . ';charset=utf8', 'bd2', 'bd2');

    }
}