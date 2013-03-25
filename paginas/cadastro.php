<?php

    // Inicializa o array $contato
    $contato = array(
        "id" => "",
        "nome" => "",
        "email" => "",
        "celular" => ""
    );

    // Enviou o ID de um contato para ser editado?
    if( isset($_GET["id"]) ) {
        
        // Armazena o id informado em uma variável
        $id_contato = (int) $_GET["id"];
     
        // Seleciona este contato, caso ele realmente seja do usuário logado
        $query = sprintf("
            SELECT 
                id, 
                nome, 
                email, 
                celular 
            FROM 
                contatos
            WHERE
                id_usuario=%d AND
                id=%d", $_SESSION["usuario_id"], $id_contato);

        // Executa a Query
        $resultado = mysqli_query($_conexao, $query);  
        
        // Retornou algum registro?
        if( mysqli_num_rows($resultado)>0 ) {            
            // Trata o resultado na forma de um array associativo
            $contato = mysqli_fetch_assoc($resultado);            
        } else {
            // Não retornou nenhum registro, então volta para a index
            irPara("index.php");            
        }
        
    }
    
?>

<!-- Carrega o arquivo cadastro.js -->
<script type="text/javascript" src="<?=SITE_URL;?>/js/cadastro.js"></script>

<ul class="breadcrumb">
  <li><a href="<?=SITE_URL;?>">Home</a> <span class="divider">/</span></li>
  <li class="active">Cadastro de contato</li>
</ul>

<!-- Exibe as mensagens de erro no cadastro/edição -->
<?php if(isset($_SESSION["erroCadastro"])): ?>
<div class="alert">
    <strong>Atenção!</strong> <?=$_SESSION["erroCadastro"];?>
</div>
<?php unset($_SESSION["erroCadastro"]); endif; ?>

<!-- Em caso de sucesso na edição, exibe a mensagem. -->
<?php if(isset($_SESSION["atualizacaoOk"])): ?>
<div class="alert alert-success">
    <strong>Feito!</strong> <?=$_SESSION["atualizacaoOk"];?>
</div>
<?php unset($_SESSION["atualizacaoOk"]); endif; ?>

<!-- Formulário para cadastro/edição -->
<form action="<?=SITE_URL;?>/processa_cadastro.php" method="post" enctype="multipart/form-data">
<div class="row">
  <div class="span4">
      <fieldset>
        <legend>Dados do contato</legend>

        <p>
            <label for="nome">Nome: </label>
            <input type="text" name="nome" id="nome" value="<?=$contato["nome"];?>" required/>
        </p>

        <p>
            <label for="email">E-mail: </label>
            <input type="text" name="email" id="email" value="<?=$contato["email"];?>" required/>
        </p> 

        <p>
            <label for="celular">Celular: </label>
            <input type="tel" name="celular" id="celular" placeholder="(99) 99999-9999" value="<?=$contato["celular"];?>" required/>
        </p>    

      </fieldset>      
  </div>
  <div class="span4">
      <fieldset>
        <legend>Foto (opcional)</legend>
        
        <p>
            <!-- Se for uma edição, tenta mostrar a foto atual do contato -->
            <img id="uploadFoto" width="70" height="70" src="<?=contatoFoto($contato["id"]);?>" alt="Contato">
        </p>

        <p>
            <label for="foto">Seleciona a foto:</label>
            <input type="file" name="foto" id="foto" onchange="visualizarFoto();">
        </p>
        
        <hr>  
        
        <!-- Se um ID de contato foi informado ... -->
        <?php if( $contato["id"]>0 ): ?>
            <!-- Este campo oculto serve para a processa_cadastro.php saber que é uma edição e não uma nova inserção -->
            <input type="hidden" name="id" value="<?=$contato["id"];?>">
        <?php endif; ?>

        <button type="submit" class="btn">Salvar Contato</button>
        
      </fieldset>
  </div>
</div>
</form>
