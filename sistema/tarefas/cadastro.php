<?php 
$Validar = 1;# deve validar o acesso/permissões
$id = empty($_REQUEST['id'])?0:$_REQUEST['id'];
$par = "$id";
$local=2;#admin
$tabela='tasklisttsk';
$pagina="index.php";?>
<?php include ('../../inc/includes.php'); ?>
<?php 
if(isset($_REQUEST['action'])):
	$ArrAux = $_REQUEST;
	if($ArrAux['ativo']==2):#foi concluído?
		$ArrAux['concluidopor'] = $_SESSION[$SIdUsuario];
		$ArrAux['concluidoem'] = date('Y-m-d H:i');
	endif;
	AutoInsertUpdate($ArrAux,$tabela);
endif;

$sql = "select 
			 titulo,
			 descricao,
			DATE_FORMAT(concluidoem,'%d/%m/%Y %H:%i') as concluidoem,
			 concluidopor,
			 status
			 $CamposAudit from ".$tabela." 
			 where  id = '$id' ";

#echo $sql;

$reg = QueryBanco($sql,1);
$limite_chars = 550;
$len_resumo = strlen($reg['descricao']);
$status = 1;
$checked="";
if($id>0):
	$status = $reg['status'];
endif;

if($status==2):
	$checked="checked";
endif;
?>
<form id="record_form_data" action="" method="post">
	<table class="table" cellpadding="0" cellspacing="0">
		<tr>
			<td width="80%">
				<label>Título:</label>
			   <input type="text" name="titulo" id="nome" value="<?php echo $reg['titulo'];?>" placeholder="Digite o título" <?php echo $estilo;?> req="true"/>
			</td>
            <td>
            <label>Concluído:</label>
            	<input type="checkbox" id="statusaux" <?php echo $estilo;?> value="2" onClick="CheckValue(this,'status',this.value,2);" <?php echo $checked; ?>/>
                <input type="hidden" name="status" id="status" value="<?php echo $status;?>" />
            </td>
            <td>
           <?php if($status==2):#concluído,exibe quem e quando  ?>
            <label>Concluído Por/Em:</label>
            	<?php echo $reg['concluidopor'];?>/<?php echo $reg['concluidoem'];?>
          <?php endif;?>
            </td>
		</tr>

		<tr>
          <td colspan="3">
          <script type="text/javascript">
		  var maxLength = <?php echo $limite_chars; ?>;  
		$('#descricao').keyup(function() {  
		  var Resumo = $('#descricao').val();
		  var textlen = maxLength - Resumo.length;		  
		  $('#rchars').text(textlen);
		  if(textlen<1){
			  Resumo = Resumo.substring(0,maxLength);
			  $(this).val(Resumo);
		  }
		}); 
		  </script>
				<label>Descrição:</label>
			   <textarea name="descricao" rows="5" id="descricao" <?php echo $estilo;?> ><?php echo $reg['descricao'];?></textarea>
              <div class="btn-warning"> <span id="rchars" style="color:#F00;font-weight:bold;"><?php echo ($limite_chars-$len_resumo); ?></span> caracteres restantes</div>
		  </td>
		</tr>
      <?php 
	  $colspan = 3;
	  if($id>0):
		  ExibeCamposAudit($id,$reg);
	  endif;?>
      	</tr>
			 <?php BotoesSalvarVoltar($id,$pagina);?>
	</table>
</form>