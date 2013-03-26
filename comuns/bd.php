<?php

/*
 * Faz a conexão com o banco de dados
 */

// Realiza a conexão
try {
    $options = array(
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    );
    $GLOBALS['pdo'] = new PDO("mysql:host={$_config["bd"]["host"]};dbname={$_config["bd"]["database"]};charset=utf8",
            $_config["bd"]["usuario"], $_config["bd"]["senha"], $options);
    unset($options);
} catch (PDOException $e) {
    exit("Erro ao realizar a conexão com o banco de dados.");
}
