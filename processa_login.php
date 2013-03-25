<?php

// Inicia a sessão
session_start();

// Requires
require_once("comuns/config.php");
require_once("comuns/bd.php");
require_once("comuns/funcoes.php");

// URL de retorno em caso de erro
$_url_retorno = SITE_URL . '/login.php';

// Os dados foram realmente recebidos?
if( empty($_POST['email']) || empty($_POST['senha']) ) {
    $_SESSION['erroLogin'] = 'Informe os dados.';
    irPara($_url_retorno); 
}

// Escapa o e-mail recebido (se necessário, por preucação).
$email = mysqli_real_escape_string($_conexao, trim($_POST["email"]));

// O e-mail recebido não é válido?
if( !validaEmail($email) ) {
    $_SESSION['erroLogin'] = 'E-mail inválido.';
    irPara($_url_retorno);
}

// Cria um hash SHA1 da senha
$senha = sha1($_POST['senha']);

// É para lembrar o e-mail? 
if( isset($_POST['lembrar-email']) ) {
    // Cria um cookie com o e-mail que expira em 20 dias.
    setcookie('usuarioEmail', $email, strtotime('+20days'));
} else {
    // Se não for, destrói o Cookie antes criado (se existir)
    setcookie('usuarioEmail');
}

// Monta a Query
$query = sprintf("SELECT id, nome, email FROM usuarios WHERE email='%s' AND senha='%s'", $email, $senha);

// Executa a Query
$resultado = mysqli_query($_conexao, $query);

// Se não retornou nenhum registro, é porquê o usuário não foi encontrado na tabela.
if( mysqli_num_rows($resultado)<=0 ) {
    $_SESSION['erroLogin'] = 'Usuário não encontrado no sistema.';
    irPara($_url_retorno);   
}

// Obtêm os dados do usuário na forma de um array associativo
$usuario = mysqli_fetch_array($resultado, MYSQLI_ASSOC);

// Armazena na sessão o id, nome e e-mail do usuário
$_SESSION['usuario_id'] = $usuario["id"];
$_SESSION['usuario_email'] = $usuario["email"];
$_SESSION['usuario_nome'] = $usuario["nome"];

// Cria a sessão que identifica que o login foi realizado
$_SESSION['logado'] = TRUE;

// Redireciona o usuário para a agenda
irPara('index.php?secao=agenda');
