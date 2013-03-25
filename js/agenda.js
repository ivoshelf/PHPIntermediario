/* 
 * Confirma a remoção de um contato
 * Curso de JavaScript Básico: http://www.treinaweb.com.br/curso/javascript
 * jQuery: Framework JavaScript (http://www.treinaweb.com.br/curso/jquery)
 * bootbox: Plugin JQuery
 */

// Ao carregar o documento
jQuery(document).ready(function(){
    
    // Delega o evento de 'onclick' aos links de remover contato
    jQuery("td.remover").find("a").click(function(event){
        
        // Armazena a URL do link na varável.
        var url = jQuery(this).attr("href");
        
        // Previne a ação padrão do link (navegar)
        event.preventDefault();
        
        // Pergunta se realmente quer remover o contato        
        bootbox.confirm("Deseja mesmo remover o contato?", function(result) { 
            
            // Se deseja remover, navega até a URL do link.
            if( result==true ) {
                document.location.href = url;
            }
            
        }); 
        
    });
    
});