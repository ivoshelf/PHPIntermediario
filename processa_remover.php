<?php

// Inicia a sessão
session_start();

// Requires
require_once("comuns/config.php");
require_once("comuns/bd.php");
require_once("comuns/funcoes.php");
require_once("comuns/seguro.php"); // Usuário está logado?

// Url de retorno
$url_retorno = SITE_URL . "/index.php";

// O Id do contato foi informado?
$id = ( isset($_GET["id"]) ) ? (int) $_GET["id"] : 0;

if( $id<=0 ) {
    $_SESSION['removerContato'] = FALSE;
    irPara($url_retorno);   
}

// Monta a Query
$stm = $GLOBALS['pdo']->prepare("
    DELETE 
    FROM 
        contatos 
    WHERE
        id_usuario=:id_usuario AND
        id=:id");
$stm->bindValue(':id_usuario', $_SESSION["usuario_id"]);
$stm->bindValue(':id', $id);
// Executa a Query
$retorno = $stm->execute();

// Se teve sucesso, então também deleta a foto do contato (se ele tiver)
if( $retorno ) {
    
    // Remove a foto do usuário da pasta de imagem (se existir)
    $foto = glob(DIR_FOTO . md5($id) . "*.{jpg,gif,png,jpeg}", GLOB_BRACE);

    // Esse caminho refere-se a um arquivo?
    if( is_file($foto[0]) ) {       
        // Remove a foto
        unlink($foto[0]);
    }
    
    // Indica que o contato foi removido
    $_SESSION['removerContato'] = TRUE;
    
} else {
    
    // Insucesso. Indica que o contato não foi removido conforme planejado.
    $_SESSION['removerContato'] = FALSE; 
    
}

// Volta para a index
irPara($url_retorno);
