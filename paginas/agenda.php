<?php

    // Seleciona todos os contatos pertecentes ao usuário logado
    $query = sprintf("
        SELECT 
            id, 
            nome, 
            email, 
            celular 
        FROM 
            contatos
        WHERE
            id_usuario=%d", $_SESSION["usuario_id"]);

    // Executa a Query
    $resultado = mysqli_query($_conexao, $query);

?>

<!-- Carrega os arquivos javascript -->
<script type="text/javascript" src="<?=SITE_URL;?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?=SITE_URL;?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=SITE_URL;?>/js/bootbox.min.js"></script>
<script type="text/javascript" src="<?=SITE_URL;?>/js/agenda.js"></script>

<!-- Erro/sucesso ao remover um contato -->
<?php if( isset($_SESSION['removerContato']) && $_SESSION['removerContato']===FALSE ): ?>
<div class="alert">
    <strong>Atenção!</strong> Erro ao remover o contato. Talvez ele não pertença à sua conta de usuário.
</div>
<?php elseif( isset($_SESSION['removerContato']) && $_SESSION['removerContato']===TRUE ): ?>
<div class="alert alert-success">
    <strong>Feito!</strong> Contato removido com sucesso.
</div>
<?php endif; unset($_SESSION['removerContato']); ?>

<table class="table table-striped table-bordered table-condensed">
    <thead>
        <tr>
            <th></th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Telefone</th>
            <th class="menor"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php while( $contato = mysqli_fetch_assoc($resultado) ): ?>
        <tr>
            <td class="center"><img width="40" height="40" src="<?=contatoFoto($contato["id"]);?>" alt="<?=$contato["nome"];?>" /></td>
            <td><a href="<?=SITE_URL;?>/index.php?secao=cadastro&id=<?=$contato["id"];?>"><?=$contato["nome"];?></a></td>
            <td><?=$contato["email"];?></td>
            <td><?=$contato["celular"];?></td>
            <td class="center remover"><a title="Remover contato" href="processa_remover.php?id=<?=$contato["id"];?>"><img src="<?=SITE_URL;?>/img/remover.png" alt="" /></a></td>
        </tr>
        <?php endwhile; ?>
        
    </tbody>
</table>

<a href="<?=SITE_URL;?>/?secao=cadastro" class="btn btn-primary novo-cadastro">Novo contato</a>
