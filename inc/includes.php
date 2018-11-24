<?php 
#iniciando sessão para todas as paginas
session_start();
require('../../config/config_ini.php');#a referência é feita a partir da pagina onde este arquivo(cabecalho.php) será incluido
require('../../inc/funcoes.php');
#nome do arquivo no qual está inserido
$arqEd = basename($_SERVER['PHP_SELF'],'.php').'.php';
include ('../../inc/rec_script.php');
if($Validar==1){#se é para validar a autenticação ANTES de exibir o menu
	ValidaAutenticacao();
}
?>