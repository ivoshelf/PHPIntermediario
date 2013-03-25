<?php

    // Inicializa a sessão
    session_start();
    
    // O usuário está logado?
    require_once("comuns/seguro.php");
    
    // Carrega o arquivo de configuração
    require_once("comuns/config.php"); 
    
    // Carrega o arquivo que inicializa a conexão com o banco de dados
    require_once("comuns/bd.php");
    
    // Carrega as funções
    require_once("comuns/funcoes.php");
    
    // Logout?
    if( isset($_GET["acao"]) && $_GET["acao"]==="logout" ) {
        // Limpa a sessão
        session_destroy();
        // Redireciona para a página de login
        irPara("login.php");
    }
    
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
      
    <div class="container" id="container-logo">        
        <a href="<?=SITE_URL;?>">
          <img src="<?=SITE_URL;?>/img/logo-treinaweb.png" alt="TreinaWeb Cursos">
        </a>        
    </div>  

    <div class="container" id="container-agenda">  
        
    <div class="masthead">
      <ul class="nav nav-pills pull-right">
        <li <?=paginaAtual('home');?>><a href="<?=SITE_URL;?>/index.php">Home</a></li>
        <li <?=paginaAtual('cadastro');?>><a href="<?=SITE_URL;?>/index.php?secao=cadastro">Cadastro</a></li>
        <li class="logout"><a href="<?=SITE_URL;?>/index.php?acao=logout">Sair</a></li>
      </ul>
        <h3>Agenda Pessoal do <span class="nome"><?=$_SESSION["usuario_nome"];?></span></h3>
    </div>

    <hr>     
        
    <?php

        // Alguma página foi informada para ser incluída?
        $secao = isset($_GET['secao']) ? $_GET['secao'] : FALSE;

        // A página informada existe no array $_config["seguras"]? 
        if( $secao!=FALSE && in_array($secao, $_config["seguras"]) )
        {
            // Caminho do arquivo PHP
            $pagina = "paginas/{$secao}.php";

            // O arquivo da página informada existe?
            if(file_exists($pagina))
            {
                // Inclui a página informada
                require_once($pagina);
            }
            else
            {
                // Inclui a página padrão
                require_once("paginas/agenda.php");
            }  
        }
        else
        {
            // Se nenhuma página válida foi informada, inclui a página padrão
            require_once("paginas/agenda.php");
        }

    ?>          
    </div>

  </body>
</html>
