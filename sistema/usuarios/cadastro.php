<?php 
$Validar = 1;# deve validar o acesso/permissões
$id = empty($_REQUEST['id'])?0:$_REQUEST['id'];
$par = "$id";
$local=2;#admin
$tabela='usuariostsk';
$pagina="index.php";?>
<?php include ('../../inc/includes.php'); ?>
<?php 
$disabecodigo = " disabled";
$disablemail = $disabecodigo;
$value="0";
$LinhaCadEfetivo = '';#Se é experimental e vai fechar contrato

if(isset($_REQUEST['action'])):
	#Debug($_REQUEST);
	AutoInsertUpdate($_REQUEST,$tabela);
endif;

$sql = "select id, 
			 nome,
			 email,
			 codigo,
			 observacao
			 $CamposAudit from ".$tabela." where id = $id";

#Debug($sql,0,1);
$reg = QueryBanco($sql,1);

$codigo = $reg['codigo'];
$Ativo = "0,1";

if($id==0):#presume-se sempre ser cadastro de aluno
	$Ativo = "1";
	$disablemail = "required";
endif;


?>
<style type="text/css">
.msg_validacao {display:none; color:#F00;text-align:center;}
#msgs{height:75px;margin:10px;}
.col_small{
	width:130px;
}
</style>
<script type="text/javascript">
var validado = false;

<?php 
#No cadastro, busca o próximo código
if($id==0):
	echo '
	//Busca o próximo código de aluno disponível quando novo cadastro
	function BuscaCodigo(){
		$(".msg_validacao").html("");
		var url = "prox_codigo_usu.php";
		$(".msg_validacao").load(url, function( response, status, xhr ) {
		  if ( status == "success" ) {
			if($(".msg_validacao").html()!=""){ 
				$("#codigo").val("");
				var codigo = $(".msg_validacao").html();
				$("#codigo").val(codigo);
				$("#codigodiv").html(codigo);
				$(".msg_validacao").html("");
			}
		  }
		});
	}	
	BuscaCodigo();';
endif;
?>
$("#email").blur(function(){
	var str = "", str = "&email="+$(this).val(),
	validado = false,
	div_id=".msg_validacao",
	url = "verifica_email.php?"+str;
	$(div_id).load(url, function(responseTxt, statusTxt, xhr){
        if(statusTxt == "success"){
			if($(div_id).html()!=""){
				$(div_id).fadeIn("fast");
				$("#btaction").attr("disabled", true);//Desabilita o botão salvar
			}else{
				$(div_id).css("display","none");
				$(div_id ).html('');
				$("#btaction").attr("disabled", false);//Habilita o botão salvar
			}
		}
    });
});



</script>
  <form id="record_form_data" action="" method="post" target="_self">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="form-horizontal">
        <div class="col-sm-2 col_small">
        <label for="disabledTextInput">CÓDIGO:</label>
          <div id="codigodiv" <?php echo $estilo;?>> <?php echo $codigo;?></div>
          <input type="hidden" name="codigo" id="codigo"  value="<?php echo $codigo;?>" />
          </div>
         </td>
        <td>
        	<label for="disabledTextInput">Nome:</label>
          	<input name="nome" type="text" id="nome"  <?php echo $estilo;?> value="<?php echo $reg['nome'];?>"  <?php echo $disabledUser;?> required />
          </td>
       <tr>
       </tr>
		 <td><label for="disabledTextInput">E-mail:</label>
          <input name="email" type="text" id="email" <?php echo $estilo;?> value="<?php echo $reg['email'] ?>"  <?php echo $disabledUser; ?>   <?php echo $disablemail; ?>/>
          </td>
           <td width="50%">
           <label for="disabledTextInput">Senha:</label>
          <input name="senha" type="password" id="senha"  <?php echo $estilo;?> value="" />
          <input name="novasenha" id="novasenha" type="hidden" value="0"/> 
          </td>
      </tr>

     <?php 
	  $colspan = 2;
	  ExibeCamposAudit($id,$reg);?>
      <tr>
        <td colspan="2" class="msg_validacao panel panel-warning" valign="middle">
			DATA
        </td>
      </tr>
        <?php BotoesSalvarVoltar($id,$pagina);?>
    </table>
  </form>
