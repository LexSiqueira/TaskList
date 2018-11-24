<?php 
$Validar = 1;# deve validar o acesso/permissões
$id = empty($_REQUEST['id'])?0:$_REQUEST['id'];
$par = "$id";
$local=2;
$tabela = 'areas_do_sistema';
$pagina="index.php";
?>
<?php include ('../../inc/includes.php'); ?>
<?php 
if(isset($_REQUEST['action'])):
	AutoInsertUpdate($_REQUEST,$tabela,1);
endif;
$sql = "select id, 
			 nome,
			 caminho,
			 aninhado_em, 
			 ordem 
			 $CamposAudit from ".$tabela." where id = '$id'";
#echo $sql;
$reg=QueryBanco($sql,1);
$ordem = 0;
$aninhado_em = 0;
if($id>0):
	$aninhado_em = $reg[3];
	$ordem = $reg[4];
endif;
?>
  <form id="record_form_data" action="" method="post" target="_self">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2"><label for="disabledTextInput">Nome</label>
          <input <?php echo $estilo;?> type="text" placeholder="Nome" name="nome" id="nome" value="<?php echo $reg[1];?>" required /></td>
      </tr>
      <tr>
        <td colspan="2"><label for="disabledTextInput">Caminho/arquivo</label>
          <input <?php echo $estilo;?> type="text" placeholder="Caminho/arquivo" name="caminho" id="caminho" value="<?php echo $reg[2];?>" /></td>
      </tr>
      <tr>
        <td>
            <label for="disabledTextInput">Aninhar em</label>
            <?php 
			$sql = "select id, nome from areas_do_sistema where aninhado_em  = 0";
			$disabled = " required ";
			ComboBanco('aninhado_em',$sql,$aninhado_em,'CarregaComboOrdem(\'IDOrdem\',this.value,'.$ordem.')');
            #ComboSubMenus($reg[3],$id);
             ?>
          </td>
          <td>
            <label for="disabledTextInput">Ordem</label>
            <div id="IDOrdem"><?php 
               ComboOrdemMenu($ordem,'areas_do_sistema',$aninhado_em);
             ?>
            </div>
             </td>
      </tr>

 <?php 
	  $colspan = 2;
	  ExibeCamposAudit($id,$reg);
	  BotoesSalvarVoltar($id,$pagina);?>

    </table>
  </form>
