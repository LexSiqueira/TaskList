<?php $titulo = "Tarefas";
$Validar = 1;# deve validar o acesso/permissões
$par       = "L";
$local     = 2;#admin
?>
<?php include ('../../inc/cabecalho.php'); ?>
<?php
$arqEd = "cadastro.php";
#Debug($_REQUEST['cliente'],0,1);
$tabela ="tasklisttsk";

$sql = "select mst.id as 'ID', 
			mst.titulo as 'Titulo',
			CONCAT(DATE_FORMAT(mst.criadoem,'%d/%m/%Y %H:%i'),'</br>',usu.nome) as 'Criado Em/Por',
			CONCAT(DATE_FORMAT(mst.concluidoem,'%d/%m/%Y %H:%i'),'</br>',uscl.nome) as 'Concluído Em/Por',
			mst.status as 'Status', 
			mst.ativo as 'Ativo', 
			mst.id as 'AÇÕES' from $tabela mst inner join usuariostsk usu  on usu.id = mst.criadopor
			left join usuariostsk uscl on uscl.id = mst.concluidopor			
			where  ";

$Alias = "mst.";#alias para identificar por qual a tabela deve filtrar o campo "ativo"
$sql .= MontaFiltroCampos($_REQUEST,$tabela);

#Debug($sql,0,0);
	AutoGrid($sql);
?>
<?php include('../../inc/rodape.php'); ?>
