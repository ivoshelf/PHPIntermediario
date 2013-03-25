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
$nome = mysqli_real_escape_string($_conexao, $_POST['nome']);
$email = mysqli_real_escape_string($_conexao, $_POST['email']);
$celular = mysqli_real_escape_string($_conexao, $_POST['celular']);

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
    $query = sprintf("INSERT INTO contatos(id_usuario, nome, email, celular) VALUES(%d, '%s', '%s', '%s')", $_SESSION["usuario_id"], $nome, $email, $celular);

    if( !mysqli_query($_conexao, $query) ) {
        $_SESSION['erroCadastro'] = 'Erro inesperado ao cadastrar o contato. Tente novamente.';
        irPara($_url_retorno);    
    }

    // Armazena na variável $id_contato o id do contato recém inserido.
    $id_contato = mysqli_insert_id($_conexao);
    
}

// Se for edição, atualiza os dados
if( $edicao ) {
    
    // Atualiza a variável $id_contato com o ID do contato que está sendo alterado
    $id_contato = (int) $_POST["id"];
    
    // Monta o Array com os novos dados
    $contato = array(
        $nome,                      // Nome
        $email,                     // E-mail
        $celular,                   // Celular
        $_SESSION["usuario_id"],    // Id do usuário logado
        $id_contato                 // Id do contato a ser alterado        
    );
    
    // Monta a Query    
    $query = vsprintf("
        UPDATE 
            contatos 
        SET 
            nome='%s',
            email='%s',
            celular='%s'
        WHERE
            id_usuario=%d AND
            id=%d", $contato);
    
    // Executa a Query
    mysqli_query($_conexao, $query);
    
    // O registro foi realmente alterado?
    if( mysqli_affected_rows($_conexao)>0 ) {
        // Sucesso na atualização, cria uma sessão para identificar isso.
        $_SESSION['atualizacaoOk'] = 'Dados alterados com sucesso!';         
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
