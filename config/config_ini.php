<?php 
#evitando cache para imagens e afins 
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


#caminho em relação ao root para controle do menu por perfil e indicar se está em DEV ou ON LINE
$system_path = "/tasklist/sistema/";
$OnLine = 1;
if(strpos($_SERVER["PHP_SELF"],"public_html")):
	$system_path = "/tasklist/sistema/";
	$OnLine = 0;
endif;

#Informações de conexão
	$host = "localhost";
	$user = "crist_utasklist";
	$pass = "yrc8N&87";
	$banco = "cristianos_TaskList";
	$External_ID = 0; #Para gravar quando for cadastro experimental pelo site
	$conexao = mysqli_connect ( $host, $user, $pass ) or die ( mysqli_error () );
	
#configurações para UTF-8
	mysqli_select_db ($conexao, $banco) or die ( mysqli_error ('Linha 22!') );
	mysqli_query($conexao,"SET NAMES 'utf8'");
	mysqli_query($conexao,'SET character_set_connection=utf8');
	mysqli_query($conexao,'SET character_set_client=utf8');
	mysqli_query($conexao,'SET character_set_results=utf8');
#Informações de sessão
	$SIdUsuario      = 'tsk_idusuario';
	$SNomeUsuario    = 'tsk_nomusu';
	$SDtUltLogon     = 'dt_ult_logon';
	$STabela         = 'tsk_tabela';
	$SPaginaLista    = 'tsk_paglista';
	$SAreadestino    = 'tsk_pgdestino';
	
	$classChecks     = "";#para exibição das opções de permissões no perfil/academia
	$VM_BTSel = 0; #Filtro para botão receitas despesas todos
	$filtros = "";
	$zeros = 2;
	$ocultaid = "";
#registros por página exibidos no grid
	$registros_por_pagina = 20;
	$total_registros = 0;
	$viacadastro = 0;
#Ativa ou desativa os fins de semana nos calendarios
$EnableWeenEnd = false;

#Caminho da imagem padrão 
	$ImagemStd = "../../imagens/comuns/imagemstd.jpg";

#Array para selecionar sim ou não (ativo/inativo)
	$ArraySN = array(1=>'Sim',0=>'Não');

#Array para controlar os campos tipo checkbox requeridos
	$ArrCheckReq = array();
	$arqEd="cadastro.php";

#pagina de listagem padrão
	$pagina			= "index.php";
	$largtabcheck	= "50%";
	#Sql para os totalizadores de relatórios
	$SQLTotal = "";

#caminho do sistema:
	$HTTP = "http://".$_SERVER['SERVER_NAME']."/";

#Array para controlar os exercícios duplicados
	$ArrExeDuplic = array();

#Array meses
	$ArrMeses[1]="Jan";
	$ArrMeses[2]="Fev";
	$ArrMeses[3]="Mar";
	$ArrMeses[4]="Abr";
	$ArrMeses[5]="Mai";
	$ArrMeses[6]="Jun";
	$ArrMeses[7]="Jul";
	$ArrMeses[8]="Ago";
	$ArrMeses[9]="Set";
	$ArrMeses[10]="Out";
	$ArrMeses[11]="Nov";
	$ArrMeses[12]="Dez";

#Array Dias da Semana
	$ArrDiaSemana[1]="Seg";
	$ArrDiaSemana[2]="Ter";
	$ArrDiaSemana[3]="Qua";
	$ArrDiaSemana[4]="Qui";
	$ArrDiaSemana[5]="Sex";
#	$ArrDiaSemana[6]="Sab";
	#$ArrDiaSemana[7]="Dom";

#Array Dias/datas na Semana
	$ArrDatasSemana['Segunda']="";
	$ArrDatasSemana['Terça']="";
	$ArrDatasSemana['Quarta']="";
	$ArrDatasSemana['Quinta']="";
	$ArrDatasSemana['Sexta']="";
#	$ArrDatasSemana['SÁBADO']="";
#	$ArrDatasSemana['DOMINGO']="";

#Array de Periodos: dias, meses ou anos
	$ArrPeriodos['DAY']="Dias";
	$ArrPeriodos['MONTH']="Meses";
	$ArrPeriodos['YEAR']="Anos";

#Array de Periodos: dias, meses ou anos
	$ArrAtivoInativo['0']="Inativos";
	$ArrAtivoInativo['1']="Ativos";
	$ArrAtivoInativo['0,1']="Todos";
	
	$ArrStatTarefas['0']	 = "Excluídos";
	$ArrStatTarefas['1']     = "Em Execução";
	$ArrStatTarefas['2']     = "Concluídos";
	$ArrStatTarefas['0,1,2'] = "Todos";
#Caminho das imagens
	define('IMAGENS','../../imagens/');

#recuperando apenas o nome do módulo(pasta) exibido para comparar ao que todos os perfis de usuário tem acesso
	$PathAux = str_replace( $system_path, '', $_SERVER["PHP_SELF"]);

#echo $_SERVER["PHP_SELF"]." - $PathAux";
	$ArrPath = explode("/",$PathAux);
	$ShownFilePath = $ArrPath[0];
	$ArquivoListagem  = $ArrPath[1];
#echo "<br /><br />".$_SERVER["PHP_SELF"]."- ".$ShownFilePath." -".$ArquivoListagem;exit;
	$ArrayPaginasPadrao = array('usuariostsk');

#estilo padrão pra campos e campo código  style="text-transform: uppercase;"
   $estilo = 'class="form-control"';
   $stylecodigo = 'style="width:150px; font-weight:bold;"';

#alias da tabela principal 
	$Alias = "mst.";
#Campos Auditoria, incluidos como ultimos campos da consulta da tela de cadastro/edição e são exibidos (somente se for edição) pela função ExibeCamposAudit
$CamposAudit = " , DATE_FORMAT(criadoem,'%d/%m/%Y %H:%i') as criadoem, criadopor, DATE_FORMAT(alteradoem,'%d/%m/%Y %H:%i') as alteradoem, alteradopor ";

#desabilita campo combo quando $disabled = "disabled";
	$disabled="";
	$value="0";
	$UsarItem=0;
	$enviamailagendamento = 0;

	$MostraHorarios = 0;
	
#para perfil usuário poder alterar somente a senha e restringir ao usuário ver apenas os seus dados
	$disabledUser = "";
	$SQLRestrictUser = "";

#Inner join com academia:
	$InnerJoinAcademia = " ";
	$InnerJoinAcademia2 = " ";
	$CampoNomeAcademia = " ";
	$InnerJoinPlano = "inner join planos_cliente pc on pc.cliente = mst.id and pc.academia = mst.academia
			inner join modalidade_planos mp on pc.plano = mp.id and mp.tipo = ";
	#and mp.academia = mst.academia
	$InnerJoinAlunoPlano = $InnerJoinPlano."1";
	$InnerJoinAcademiaPlano = $InnerJoinPlano."2";
	$CssContas = ' input.text { margin-bottom:5px; width:95%; padding: .3em; }
    div#users-contain { width: 100%; margin: 5px 0; }
    div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
    div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .3em 5px; text-align: left; }
	#cliente,#produto {width:150px;}
	.frigth{float:right;}
	
	/* Limitando o autocomplete*/
	.ui-autocomplete {
    max-height: 300px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  }
  /* IE 6 doesnt support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
  * html .ui-autocomplete {
    height: 100px;
  }
  #divError{display:none;background-color:transparent;}
  #descricao_span{width:100%; height:35px;}';
#botão dos forms
$btcancelform = '/* botão cancelar dos forms*/
$("#cancelar").click(function(){
  $( "#divError" ).hide();
  $("#dialog_form").dialog("close");
  $("#content_form").html( "" );//limpa o conteudo do div
  window.location.reload();//refresh tela
});
';
#CRC para os includes serem os mais atualizados
$CRCTime = str_replace(" ","",microtime());

#para retornar o código HTML gerado ou imprimir em tela (echo)
	$return       = 0;
	$colspan      = 0;
#	echo "CONFIG OK!";
?>