<?php $titulo = "Áreas do Sistema";
$Validar = 1;# deve validar o acesso/permissões
$par = "L";
$local=2;?>
<?php include ('../../inc/cabecalho.php'); ?>
<?php
$tabela ="areas_do_sistema";

$sql = "select mst.id as 'ID', 
			  mst.nome as 'Nome',
			  mst2.nome as 'Aninhado Em',
			  mst.ativo as 'Ativo', 
			  mst.id as 'AÇÕES'  from ".$tabela." mst 
			  $InnerJoinAcademia
			  left join areas_do_sistema mst2 on  mst.aninhado_em = mst2.id
			  where  ";

$Alias = "mst.";#alias para identificar por qual atabela deve filtrar o campo "ativo"
$sql .= MontaFiltroCampos($_REQUEST,$tabela);
#echo $sql;

	AutoGrid($sql);

?>
<?php include('../../inc/rodape.php'); ?>