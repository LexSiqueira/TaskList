<?php 
#iniciando sessão para todas as paginas
session_start();
require('../../config/config_ini.php');#a referência é feita a partir da pagina onde este arquivo(cabecalho.php) será incluido
require('../../inc/funcoes.php');
if($Validar==1){#se é para validar a autenticação ANTES de exibir o menu
	ValidaAutenticacao();
}
$titulo = ExibirTitCadEd($titulo,$par);
#para buscar sempre o funcoes atualizado
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="">
<meta name="author" content="Lex Siqueira">
<meta name="application-name" content="takslist">
<meta name="viewport" content="width=divice-width, initial-scale=1.0">
<link rel="shortcut icon" href="../../imagens/comuns/siteicon.ico"/>
<title><?php echo $titulo; ?></title>
<script type="text/javascript" src="../../inc/jquery.min.js"></script>
<!-- Núcleo do Bootstrap CSS -->
<script src="../../dist/js/bootstrap.min.js"></script>
<link href="../../dist/css/bootstrap.min.slate.css" rel="stylesheet">
<script type="text/javascript" src="../../jquery-ui/jquery-ui.min.js"></script>
<link href="../../jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script type="text/javascript" src="../../inc/shortcut.js?crc=<?php echo $CRCTime;?>"></script>
<script type="text/javascript" src="../../inc/funcoes.js?crc=<?php echo $CRCTime;?>"></script>
</head>
<body>
<?php
CabecalhoSite($local);
echo '<div id="conteudo" class="largura_interna relativa">';
?>
