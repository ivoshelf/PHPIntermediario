<?php

    // Inicializa a sessão
    session_start();

    // Carrega o arquivo de configuração
    require_once("comuns/config.php");
    
    // Busca (se existir) o e-mail armazenado em Cookie
    $email = ( isset($_COOKIE["usuarioEmail"]) ) ? $_COOKIE["usuarioEmail"] : "";

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Agenda Pessoal | TreinaWeb Cursos</title>
    <!-- Estilos CSS -->
    <link href="<?=SITE_URL;?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=SITE_URL;?>/css/agenda.css" rel="stylesheet">
  </head>

  <body>
      
    <!-- Login -->  
    <div class="container" id="container-login">
        <form class="form-signin" method="post" action="processa_login.php">
            <h2 class="form-signin-heading">Por favor, entre:</h2>
            
            <?php if(isset($_SESSION["erroLogin"])): ?>
                <div class="alert alert-error"><?=$_SESSION["erroLogin"];?></div>
            <?php unset($_SESSION["erroLogin"]); endif; ?>
            
            <input type="text" class="input-block-level" placeholder="Email" name="email" value="<?=$email;?>" required>
            <input type="password" class="input-block-level" placeholder="Senha" name="senha" required>
            
            <label class="checkbox">
              <input type="checkbox" name="lembrar-email" value="true"> Lembrar e-mail
            </label>
            
            <button class="btn btn-large btn-primary" type="submit">Entrar</button>
        </form>
        <small><a href="http://www.treinaweb.com.br" target="_blank">TreinaWeb Cursos</a></small><br>
    </div>
    <!-- / Login --> 

  </body>
</html>
