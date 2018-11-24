<script type="text/javascript">
var divError = '#divError';
$(function() {
	var ArrMissFild = new Array();
	$("#btaction").click(function(){
		event.preventDefault();

		var campos = $( "#record_form_data" ).serialize(),
		 msg_body = "Cadastro efetuado!",
		 id = $("#id").val();
		if(id>0){
			msg_body = "Dados do registro ["+ id+ "] atualizados!";
		}

		if(validar("record_form_data")){
			//debug
			//var msg = "  - <?php echo $arqEd; ?>"+"?"+campos;
			//console.log(msg);
			//alert(msg); //removido a pedido do Rafa em 17/05/2017
			$.post("<?php echo $arqEd; ?>", campos, function(result){
				//Exibir msg
				$("#msg_body").html(msg_body);
				$("#msg_salvo").fadeIn( 400 ).delay( 1500 ).fadeOut( 400, function() {
					// Quando terminar, recarrega
					window.location.reload();
				 });
			});//POST
			$("#btn_msg_ok").click(function() {
				//recarrega
				window.location.reload();
			 });
			
		}
	});//#action
	
	function validar(form){
		// determina se o form pode ser submetido ou não
		var canSubmit = true;
		
		// acumula as mensagens de erro
		var messages = "<strong>Preencha os Campos</strong><ul>";
		// faz uma busca por todos elementos que especificam o atributo req=true  " "+$(this).attr("id")+
		$("#"+form+" [required]").each(
			function(){
				if($(this).val().length < 1){
					canSubmit = false;
					messages += "<li> teste" + $(this).attr("id")+ "</li>";
					ArrMissFild.push($(this).attr("id"));
				}
			}
		);
		messages += "</ul>";
		
		// verifica se vai exibir as mensagens de erro
		if(canSubmit == false){
			//Percorrendo os campos incorretos
			for(var i=0;i < ArrMissFild.length;i++){
				$("#"+ArrMissFild[i]).addClass( "ui-state-error" );
			}
			$("#msgerr").html(messages);
			$(divError).show(500);
			$(divError).addClass( "ui-state-highlight" );
		  setTimeout(function() {
			$(divError).removeClass( "ui-state-highlight", 1000 );
		  }, 500 );
		}//cansubmit
		return canSubmit;
	}
	
	$("#closemsgerr").click(function() {
	//Percorrendo os campos incorretos
		for(var i=0;i < ArrMissFild.length;i++){
			$("#"+ArrMissFild[i]).removeClass( "ui-state-error" );
		}
      	$( divError ).hide(500);
    });
	
	$(".ui-icon-closethick").click(function() {
		//Ao clicar no botão fechar da caixa de diálogo, recarrega
		window.location.reload();
    });
	
<?php echo $btcancelform; ?>
});//ONLOAD
</script>
<style type="text/css">
	#msg_salvo{
		position:fixed;
		top:25%;
		left:35%;
		z-index:1000;
		width:450px;
		margin:auto;
		text-align:center;
		display:none;
	}
</style>
<div  id="msg_salvo" class="panel panel-success">
  <div class="panel-heading" style="font-size:18px; text-align:center;"><strong>SUCESSO!!</strong></div>
  <div class="panel-body" id="msg_body">
    
  </div>
  <button type="button" class="btn btn-sm btn-success" id="btn_msg_ok"> <span class="glyphicon glyphicon-ok"></span>OK</button>
</div>