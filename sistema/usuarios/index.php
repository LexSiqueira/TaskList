<?php $titulo = "Clientes";
$Validar = 1;# deve validar o acesso/permissões
$par = "L";
$local=2;#admin?>
<?php include ('../../inc/cabecalho.php'); ?>
<?php
$_REQUEST['inativo'] = !isset($_REQUEST['inativo'])?1:$_REQUEST['inativo'];
$tabela = "usuariostsk";
$tipo = 1;
#Exibe apenas alunos
$sql = "select mst.id as 'ID',
			mst.codigo as 'Código',
			mst.nome as 'Nome', 
			mst.email as 'E-mail', 
			DATE_FORMAT(mst.dt_ult_logon,'%d/%m/%Y %H:%i') as 'Último Logon', 
			mst.ativo as 'Ativo', 
			mst.id as 'AÇÕES' from $tabela mst 
			where ";
$Alias = "mst.";#alias para identificar por qual atabela deve filtrar o campo "ativo"


#Debug($_REQUEST,1,0);
$sql .= MontaFiltroCampos($_REQUEST,$tabela);
#TelaDebug($sql,0,0);
AutoGrid($sql);
?>
<?php include('../../inc/rodape.php'); ?>