<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 22/11/14
 * Time: 15:03
 */
// Start the session
if(!isset($_SESSION)){
    session_start();
}

class Common {

    /*
     * CANVIAR AQUI L'USUARI I LA CONTRASENYA DE LA BASE DE DADES AMB AQUESTA VARIABLE
     */
    private static $usuari = 'bd2';
    private static $constrasenya = 'bd2';

    public static function initPDOConnection($dbName) {
        return new PDO('mysql:host=localhost;dbname=' . $dbName . ';charset=utf8', self::$usuari, self::$constrasenya);
    }

    //Function to check if the request is an AJAX request
    public static function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}