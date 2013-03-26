<?php

// Inicia a sessão
session_start();

// Requires
require_once("comuns/config.php");
require_once("comuns/bd.php");
require_once("comuns/funcoes.php");
require_once("comuns/seguro.php"); // Usuário está logado?

// É edição ou inserção?
$edicao = ( isset($_POST["id"]) ) ? TRUE : FALSE;

// URL de retorno em caso de erro
$_url_retorno = ( $edicao ) ? SITE_URL . "/index.php?secao=cadastro&id=" . (int) $_POST["id"] : SITE_URL . "/index.php?secao=cadastro";

// Os dados foram realmente recebidos?
if( empty($_POST['email']) || empty($_POST['nome']) || empty($_POST['celular']) ) {
    $_SESSION['erroCadastro'] = 'Informe os dados.';
    irPara($_url_retorno); 
}

// Armazena os dados em variáveis escapando-os
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$celular = filter_input(INPUT_POST, 'celular', FILTER_SANITIZE_STRING);

// O nome deve ter no mínimo 3 caracteres
if( strlen($nome)<3 ) {
    $_SESSION['erroCadastro'] = 'O nome deve ser maior que 3 caracteres.';
    irPara($_url_retorno);   
}

// O e-mail recebido não é válido?
if( !validaEmail($email) ) {
    $_SESSION['erroCadastro'] = 'E-mail inválido.';
    irPara($_url_retorno);
}

// O número do celular recebido não é válido?
if( !validaCelular($celular) ) {
    $_SESSION['erroCadastro'] = 'Número de celular inválido.';
    irPara($_url_retorno);
}

// Se for uma inserção de contato
if( !$edicao ) {
    
    // Cadastra o contato na tabela de "contatos"
    $stm = $GLOBALS['pdo']->prepare("INSERT INTO contatos(id_usuario, nome, email, celular) VALUES(:id_usuario, :nome, :email, :celular)");   
    
}

// Se for edição, atualiza os dados
if( $edicao ) {
    
    // Atualiza a variável $id_contato com o ID do contato que está sendo alterado
    $id_contato = (int) $_POST["id"];
    
    // Monta a Query    
    $stm = $GLOBALS['pdo']->prepare("
        UPDATE 
            contatos 
        SET 
            nome=:nome,
            email=:email,
            celular=:celular
        WHERE
            id_usuario=:id_usuario AND
            id=:id");
    $stm->bindValue(':id', $id_contato);
            
}

if ($stm instanceof PDOStatement) {
    $stm->bindValue(':id_usuario', $_SESSION["usuario_id"], PDO::PARAM_INT);
    $stm->bindValue(':nome', $nome, PDO::PARAM_STR);
    $stm->bindValue(':email', $email, PDO::PARAM_STR);
    $stm->bindValue(':celular', $celular, PDO::PARAM_STR);
    $resultado = $stm->execute();
    if ($resultado === true) {
        if ($edicao) {
            $_SESSION['atualizacaoOk'] = 'Dados alterados com sucesso!';  
        }else {
            $id_contato = $GLOBALS['pdo']->lastInsertId();
        }
    } else {        
        $_SESSION['erroCadastro'] = 'Erro inesperado ao cadastrar o contato. Tente novamente.';
        irPara($_url_retorno);
    }
}

// Se uma foto foi selecionada, tenta upá-la
if( !empty($_FILES['foto']['tmp_name']) || $_FILES['foto']['tmp_name']!='none' ) {
    uploadFoto($_FILES['foto'], $id_contato);
}

// Se for uma inserção, volta para a index, se for uma edição, volta para a página que edita o contato
if( !$edicao ) {
    irPara(SITE_URL . "/index.php");
} else {
    irPara($_url_retorno);
}
