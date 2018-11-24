<?php
#INICIO funcão AutoGrid
function AutoGrid($sql){
	#disponibilizando as variáveis globais
	global $arqEd,$ArrDiaSemana, $estilo,  
	$SPaginaLista,$filtros , $registros_por_pagina, $titulo, $ArquivoListagem, 
	$ShownFilePath , $Alias, $post, $palavra, $return,$value,  $local, 
	$total_registros, $disabled, $SIdUsuario, $ocultaid, $ArrPeriodos, 
	$ArrAtivoInativo, $ArrStatTarefas, $tabela, $tipo, $ArrMeses;

	$tituloExibe = AjustaTitulo($titulo);
	#Debug($tituloExibe,0,1);
	
	#ordenação
	$ordem = empty($_REQUEST['ordem'])?$Alias."id":$_REQUEST['ordem'];
	$direcao = empty($_REQUEST['direcao'])?'asc':$_REQUEST['direcao'];
	$inativo = empty($_REQUEST['inativo'])?'0,1':$_REQUEST['inativo'];
	$status = empty($_REQUEST['status'])?'0,1,2':$_REQUEST['status'];

	#colocando a ? ou o &
	if(!strpos($arqEd,"?")):
		$arqEd = $arqEd."?";
	else:
		$arqEd = $arqEd."&";
	endif;
#Debug($arqEd,0,1);
	$busca = empty($_REQUEST['busca'])?"":$_REQUEST['busca'];
	#setando filtros para a paginação
	if(strlen($busca)>0):
		$filtros .= "&busca=$busca";
	endif;
#	Debug($filtros,0,0);
	if(isset($palavra)&&strlen($palavra)>3):
		$filtros .= "&palavra=$palavra";
	endif;

	if(isset($_REQUEST['inativo'])):
	     $filtros .= "&inativo=".$_REQUEST['inativo'];
	endif;

	$arqLst = $ArquivoListagem;#arquivo de listagem de informações
#Debug($_GET,1,0);
#Debug($filtros,0,0);
#Debug($sql,0,0);

	$resX = QueryOrDie($sql,$tabela);
	$total_registros = mysqli_num_rows($resX);
	
	$pagina = empty($_GET['pagina'])?1:$_GET['pagina'];
	require_once('../../inc/paginacaof.php');

	$_SESSION[$SPaginaLista] = $ShownFilePath.'/'.$ArquivoListagem;
	#Debug($filtros,0,0);
	if(strpos($_SESSION[$SPaginaLista],"?")):
		$_SESSION[$SPaginaLista] .= "&";
	else:
		$_SESSION[$SPaginaLista] .= "?";
	endif;
	$_SESSION[$SPaginaLista] .= "pagina=$pagina&".$filtros;
	
#Quando não for a tabela posts
	$OpenForm = " open-form";
#Debug($sql,0,0);
	$res = QueryOrDie($sql,$tabela);
#Debug("SQL-> ".$sql,0,0);

	#Manter o botao Editar na parte Contas a Pagar receber
	$arq = '';
	$acao_form = $ArquivoListagem;
	$total_campos = mysqli_num_fields($res);
	$LabelCheck="INATIVOS";
	$buscaDIV = '<div class="busca">
			<form id="filtrar_results" class="form-inline" action="'.$ArquivoListagem.'" method="post">
			<div class="btn-toolbar">';
	$PalavraChave = '<input type="text" class="form-control" placeholder="Cod./Palavra Chave" name="palavra" value=""/>';
	$btadd='<button type="button" class="btn btn-primary" title="(F2)" id="Adicionar" value="0">
		<span class="glyphicon glyphicon-plus-sign"></span>
		Adicionar
		</button>';

	$busca .= $PalavraChave;
	
	$selTodos = "";
	$selBloq = "";
	$selTrc = "";

	$return = 1;

	#se não for tabela de tarefas, não exibe a opção "concluidos"
	if($tabela == "tasklisttsk"):
		$busca .= ComboArray('status', $ArrStatTarefas, $status,'','STATUS');
	endif;

	$busca .= ComboArray('inativo', $ArrAtivoInativo, $inativo,'','SITUAÇÃO');
	$return = 0;
	$busca .= ' <input class="btn btn-primary" type="submit" id="action" value="Buscar" />
	<input class="btn btn-primary" type="button" id="limpa_busca" value="Limpar Filtros"  onclick="LinkSimples(\''.$ArquivoListagem.'\');"/>
	
	  </div><!-- /btn-toolbar -->
	<input type="hidden"  name="busca" id="busca" value="busca"/> 
</form>
</div> <!--busca-->
	<br />';
	$value ="";
	$busca .= OpenFormDialog($tabela,$OpenForm,$tituloExibe);
	$titulo = '<h3 class="list-header">'.$titulo.'</h3>';
	echo $buscaDIV.$busca.$titulo.$btadd;
	$larguratbs='100%';
	echo '<table width="'. $larguratbs.'" border="0" cellspacing="0" class="table" cellpadding="0">';
	ExibePaginacao($pagina,$total_paginas,$total_campos,$filtros,$pag_anterior,$pag_posterior);
	$sql .= " order by $ordem $direcao ";
#Debug($sql,0,0);

	#apos consultar pela direçao, inverte a opcao
	if($direcao =='desc'):
		$direcao ='asc';
	else:
		$direcao ='desc';
	endif;#direcao=='desc'
    echo '<tr class="cabe_grid">
	<td colspan="'.$total_campos.'" class="erro">Encontrado(s) <strong>'.$total_registros.'</strong> registro(s) </td>
	</tr>
	  <tr class="cabecalhotabela">';
	  $fieldinfo=mysqli_fetch_fields($res);
	  #for($i=0;$i<$total_campos;$i++):
	  $i=0;
	  foreach($fieldinfo as $val):
		$nome_campo = $val->name;
		$nome_campo = ucfirst($nome_campo);
		$string = "";
		$string .= "<td";
		$porcent =  ((int)(100/($total_campos)))."%";
		$ordenacaoI = "";
		$ordenacaoF = "";
		$ordenacaoI = '<a href="'.$arqLst.'?'.$filtros.'&direcao='.$direcao.'&ordem='.($i+1).'&pagina='.$pagina.'" title="Ordenar por '.$nome_campo.'">';
		$ordenacaoF = "</a>";
		$NomeUpper = strtoupper($nome_campo);
		$cssocultaid = "col_normal";		
		if($NomeUpper=='CÓDIGO')://se é o campo do ID 
			$porcent = "9%";
			$cssocultaid = "col_cod";
		elseif($nome_campo=='AÇÕES')://se é o campo do AÇÕES 
			$cssocultaid .= " col_acoes";
			$porcent = "15%' align='center";
			$ordenacaoI = "";
			$ordenacaoF = "";
		endif;
		#Se deve ocultar o campo ID ou não
		if($nome_campo=='ID'&&$ocultaid!=""):
				$cssocultaid .= " oculto";
		endif;
		$string .= "  class='$cssocultaid' width='$porcent'>";
		$string .= $ordenacaoI.$nome_campo.$ordenacaoF;
		$string .="</td>";
		echo $string;
		$i++;
	  endforeach;

	  #atualizando os filtros com a ordenação
	  $filtros.="&ordem=".$ordem;
	  echo '</tr> 
  <tbody>';
	 if($total_registros>0):
		$class="";
		while($reg=mysqli_fetch_array($res)):
			
			$altura = " height='25'";
			$classAd = "";
			if($class=="tr1"):
				$class="tr2";
			else:
				$class="tr1";
			endif;
			//Se estiver inativo, o texto fica em vermelho
			if(isset($reg['ATIVO'])&&$reg['ATIVO']==0):
				$classAd = " danger";
			endif;
			
			echo '<tr class="'.$class.$classAd.'">'; 
			 $fieldinfo=mysqli_fetch_fields($res);
			#for($i=0;$i<$total_campos;$i++):
			$i = 0;
			foreach ($fieldinfo as $val):
				$nome_campo = $val->name;
				$nome_campo = strtoupper($nome_campo);
				
				$cssocultaid = "col_normal";		
				if($nome_campo=='AÇÕES'||$nome_campo=='CÓDIGO')://se é o campo do AÇÕES 
					$cssocultaid .= " col_acoes";
				endif;
				
				if($nome_campo=='ID'&&$ocultaid!=""):
					$cssocultaid .= " oculto";
				endif;
				echo "<td class='$cssocultaid' $altura > ";
				if($nome_campo!='AÇÕES'):
					switch($nome_campo):
						case 'ATIVO':
							$idAtivar = $reg[0];
							$situacao =  $reg[$i];
							#Para exibir ATIVAR/DESATIVAR e icone correto, a princípio está ATIVO (1)
							$ExibLabel="ATIVO";
							$ExibTitle="EXCLUIR";
							$ACAO = '0';
							$icon = "ban-circle";
							$btnsituacao = "success";
							if($situacao=="0"):
								$ExibLabel="EXCLUÍDO";
								$ExibTitle="RESTAURAR";
								$btnsituacao = "danger";
								$ACAO = '1';
								$icon = "ok-circle";
							endif;
							
							echo '<div id="ShowSituacao">
							<button type="button" class="btn btn-sm btn-'.$btnsituacao.'" title="'.$ExibTitle.'" onclick="confirm_trocasituacao('.$reg[0].',\''.$ACAO.'\',\''.$reg[1].'\',\''.$tabela.'\',\'ativo\')">
								<span class="glyphicon glyphicon-'.$icon.'"></span>
							</button>
						</div>';
						break;
						case 'STATUS':
							$idAtivar = $reg[0];
							$situacao =  $reg[$i];
							#Para exibir EXCLUÍDO/EXECUTANDO/CONCUÍDO e icone correto, a princípio está ATIVO (1)
							switch($situacao):
								case "0":
									$ExibLabel="EXCLUÍDO";
									$ExibTitle="RESTAURAR";
									$btnsituacao = "danger";
									$ACAO = '1';
									$icon = "ok-circle";
								break;
								case "1":
									$ExibLabel="EXECUTANDO";
									$ExibTitle="CONLCUIR";
									$btnsituacao = "danger";
									$ACAO = '2';
									$icon = "ok-circle";
								break;
								default:
									$ExibLabel="CONCUÍDO";
									$ExibTitle="ABRIR";
									$ACAO = '1';
									$icon = "ban-circle";
									$btnsituacao = "success";
								break;

							endswitch;
							
							echo '<div id="ShowSituacao">
							<button type="button" class="btn btn-sm btn-'.$btnsituacao.'" title="'.$ExibTitle.'" onclick="confirm_trocasituacao('.$reg[0].',\''.$ACAO.'\',\''.$reg[1].'\',\''.$tabela.'\',\'status\')">
								<span class="glyphicon glyphicon-'.$icon.'"></span>
							</button>
						</div>';
						break;
						case 'NOMECAMPO':
							echo '';
						break;

						default:
							echo $reg[$i];
						break;
					endswitch;
				else: #AÇOES
					echo ' <button type="button" class="btn btn-sm btn-primary '.$OpenForm.'" title="Editar" value="'.$reg[$i].$arq.'">
						<span class="glyphicon glyphicon-edit"></span>
					</button>';
				endif;//ACOES
				echo "</td>";	
				$i++;
		   endforeach;
		  echo '</tr></tbody>';
	  endwhile;
	 else://if($total_registros>0):
#	 if($registros_por_pagina<2000):
		  echo '</tbody><tr>
			<td colspan="'.($total_campos).'" align="center" class="bg-warning">Nenhum item econtrado! </td>
		  </tr>';
		endif; //if($total_registros>0):
		ExibePaginacao($pagina,$total_paginas,$total_campos,$filtros,$pag_anterior,$pag_posterior);
	  echo '
	</table> '; 	
}
#FIM funcão AutoGrid

function ExibePaginacao($pagina,$total_paginas,$total_campos,$filtros,$pag_anterior,$pag_posterior,$sufixo="",$retorno=0){
	global $arqLst;
	$paginacao = "";
	if($total_paginas>1):
		$max_links = 8;
		$links_laterais = ceil($max_links / 2);
		$inicio = $pagina - $links_laterais;
		$limite = $pagina + $links_laterais;
		$linkponto = '<span class="btn btn-default disabled">.</span>';
		$paginacao .= '<tr><td colspan="'.($total_campos).'" align="center" >
			PÁGINAS<br />
			<div class="btn-toolbar pagination">
			<div class="btn-group">';
		$classA = ' class="btn btn-';
		$classB = $classA;
		$classC = 'success"';
		if($pagina<=1){
			$classC = 'warning disabled"';
		}
		$class = $classA . $classC; 
		$paginacao .= "<a href='".$arqLst."?pagina$sufixo=$pag_anterior".$filtros."'  '.$class.'>&laquo;</a>";
		if($inicio>1){
			$paginacao .=  "<a href='".$arqLst."?pagina$sufixo=1' $class >1</a>";
		}
		if($inicio>=$links_laterais){
				$paginacao .= $linkponto.$linkponto.$linkponto;
			}
		for($i=$inicio;$i<=$limite;$i++):
			$classC = 'success"';
			$n = $i;
			if($i==$pagina){
				$link = '#';
				$classC = 'warning disabled"';
				$n = "[$i]";
			}
			
			if ($i >= 1 && $i <= $total_paginas){
					$class = $classB . $classC;
					$paginacao .= "<a href='".$arqLst."?pagina$sufixo=$i".$filtros."' $class>$n</a>";
			}
		endfor; 
		$classC = ' disabled"';
		if($limite<=($total_paginas-$links_laterais)){
			$paginacao .=  $linkponto.$linkponto.$linkponto;
		}
		if($i<$total_paginas){
			$paginacao .=  "<a href='".$arqLst."?pagina$sufixo=$total_paginas' $class >$total_paginas</a>";
		}
		if($pagina<$total_paginas){
			$classC ='"';
		}			
		
		$paginacao .=  "<a href='".$arqLst."?pagina$sufixo=$pag_posterior".$filtros."' $class>&raquo;</a> 
			</div>
		</div>
		</div>";
		$paginacao .= '</td></tr>';

	  endif;#$total_paginas>1
	  
	  if($retorno):
		  return $paginacao;
	  else:
	  	echo $paginacao;
	 endif;
}

#INICIO função para exibição do cabeçalho
function CabecalhoSite($local){
	global $SIdUsuario, $HTTP, $titulo, $local, $id,  $linkcomum;
	$tituloExibe = $titulo;

	$conteudo="";

	$conteudo = '<!-- Estilos personalizados  para o ADMIN -->
	<link href="../../inc/AdminTheme.css" rel="stylesheet"/>';
	if(isset($_SESSION[$SIdUsuario])):#logado
		MenuAdmin();
	else:#nao logado
		$conteudo .= '<img src="'.IMAGENS.'comuns/logo_sistema.png" id="logo" height="100">';
	endif;
#	$conteudo .= "<div class='espaco'></div>";
	echo $conteudo;
}
#FIM função para exibição do cabeçalho

#INICIO função para exibição do MenuAdmin
function MenuAdmin($OcultaMenu=0){
	global $SIdUsuario, $par;
	$fonte12 = '';
	 ?>
    <!--INICIO MENU -->
     <div class="navbar navbar-default navbar-fixed-top" role="navigation" style="font-size:12px;font-weight:bold;">
         <div class="container-fluid">
           <?php 
		   $NavbarHeader = ' <div class="navbar-header">
           <button type="button" class="navbar-toggle" data-toggle="collapse"data-target=".navbar-collapse">
             <span class="sr-only">Toggle navigation</span> 
              <span class="icon-bar"></span> 
              <span class="icon-bar"></span> 
              <span class="icon-bar"></span>
           </button>';
		   $ALink = '<a href="../tarefas/index.php">';
		   $FLink = '</a>';
		   #Não deve exibir link
		   if($OcultaMenu):
			   $ALink = '';
			   $FLink = '';
			   $NavbarHeader = '';
		   endif;
		   $LinkLogo = '<img src="../../imagens/comuns/logo_sistema.png" id="logo_sistema">';
		   echo $NavbarHeader;
		   echo $ALink.$LinkLogo.$FLink;
		   	?>
          </div>
          <div class="navbar-collapse collapse">
          	<div id="menu">
                <ul class="nav navbar-nav">
                  <!--li><a href="../agendamentos/?busca=dia">Agenda</a></li>
        <!--INICIO MENU DINAMICO -->
        <?php $linksair='';
         if(!$OcultaMenu):#deve exibir
                $SqlMenus = "Select id, nome, IF(LENGTH(caminho)=0, '' ,CONCAT('../',caminho,'/')) as caminho from areas_do_sistema where aninhado_em=0 and ativo=1 order by ordem";
    #Debug($SqlMenus,0,1);
                $QueryMenus = QueryOrDie($SqlMenus,"Menu");
                $totalM = mysqli_num_rows($QueryMenus);
                if($totalM>0):
                    while($RegMenus = mysqli_fetch_array($QueryMenus)):
                        $link="#";
                        $DropDown=' class="dropdown"';
                        $DropDown2=' class="dropdown-menu"';
                        $ArrowDown=' <b class="caret"></b>';
                        $Dropdowntoggle=' class="dropdown-toggle" data-toggle="dropdown"';
                        
                        #verificando se há link no campo "caminho"
                        if($RegMenus[2]!=""):
                            $link = $RegMenus[2];
                            $DropDown='';
                            $DropDown2='';
                            $ArrowDown='';
                            $Dropdowntoggle='';
                        endif;
                        
                        $nome_link = $RegMenus[1];
    
                        #exibindo menus
                        echo '
                        <li'.$DropDown.' >
                            <a href="'.$link.'"'.$Dropdowntoggle.' >'.$nome_link.' '.$ArrowDown.'</a>
                            <ul'.$DropDown2.' style="'.$fonte12.'">';
                        #recuperando os sub-menus a partir da sessão do usuário logado
                        $SqlSubMenus = "Select CONCAT('../',caminho) as caminho, nome from areas_do_sistema where aninhado_em=".$RegMenus[0]." and ativo=1  order by ordem";
    #echo "<br/><br/><br/>".$SqlSubMenus;
                        $QuerySubMenus = QueryOrDie($SqlSubMenus,"Sub Menus");
                        $totalS = mysqli_num_rows($QueryMenus);
                        if($totalS>0):
                            while($RegSubMenus = mysqli_fetch_array($QuerySubMenus)):
                                $link      = $RegSubMenus[0];
                                $nome_link = $RegSubMenus[1];
                                $telao = "";
                                #Se link de telão
                                 if(substr($nome_link,0,6)!="Telão"):
                                /*	$link = "javascript:;";
                                    #Abrindo pop do telão certo
                                    $n = str_replace("Telão ","",$nome_link);
                                    $telao = " onclick='Treinos_Telao($n,0,0)'";
                                    $nome_link .= '</a> <a href="'.$link.'" onclick="Treinos_Telao('.$n.',0,1)">Coletivo '.$n;
                                    */
                                #exibindo submenus
                                echo '<li><a href="'.$link.'" '.$telao.'>'.$nome_link.'</a></li>';
                                endif; 
    
                            endwhile;
                        endif;#$totalS>0
                        echo '</ul></li>';
                    endwhile;
                endif;#$totalM>0

        ?>
        </ul>
    <!--FIM MENU DINAMICO  -->
     <?php
		 $linksair='    <!-- LINK SAIR É FIXO -->
		 <ul class="nav navbar-nav navbar-right">
			<li><a href="../logout/">Sair</a></li>
		 </ul>';

	 echo $linksair;
	endif;#Oculta menu ?>
    		</div><!--/#menu -->
          </div> <!--/.nav-collapse -->
         </div> <!--/.container-fluid -->         
        </div> <!--/.navbar-->
    <!--FIM MENU -->
    <?php
}
#FIM função para exibição do MenuAdmin

#INICIO função que exibe os botões salvar/voltar e ID do registro
function BotoesSalvarVoltar($id,$pagina,$carga=0){
	global $tabela,$colspan, $return;
	$trcolspan="";
	if($colspan>0):
		$trcolspan='colspan="'.$colspan.'"';
	endif;
	$trA = '<tr>
	<td '.$trcolspan.' width="100%" height="60" align="right">';
	$trF="</td>
	</tr>";
	$bts = '<input type="hidden" name="id" id="id" value="'.$id.'">
	<input type="hidden" name="action" value="salvar">
	<input type="hidden" name="__addPlus" id="__addPlus" value="">
          <button class="btn btn-success" title="Salvar" type="submit" id="btaction" value="salvar"> <span class="glyphicon glyphicon-floppy-saved"></span> Salvar </button>';
  $bts .= '
  <button class="btn btn-primary" type="button" id="cancelar">
	  <span class="glyphicon glyphicon-floppy-remove"></span> Fechar
  </button>';
		if($return):
			return $trA.$bts.$trF;
		else:
		  echo $trA.$bts.$trF; 
		 endif;
}
#FIM função que exibe os botões salvar/voltar e ID do registro

#INICIO função que converte formatos de data
function converte_data($data,$de){
	$hora = "";
	if(($data!='0000-00-00')&&strlen($data)>10):
		$dataA = explode(" ", $data);
		$data = $dataA[0];
		$hora = " ".substr($dataA[1],0,5);
	endif;

	if(($data!='0000-00-00')&&strlen($data)==10):
		$separa="-";
		if($de=="/"):
			$separa="-";
		else:
			$separa="/";
		endif;
		
		$dataN = explode($de, $data);
	//print ($separa. $dataN); exit;
		return $dataN[2].$separa.$dataN[1].$separa.$dataN[0].$hora;
	else:
		return "";
	endif;
}
#FIM função que converte formatos de data

#INICIO função que exibe os campos de auditoria na tela de cadastro/edicão somente se for edição
function ExibeCamposAudit($id,$Registro){
	global $colspan,$SIdUsuario;
	#se é edição o id>0
	#busca os dados dos usuários
	$sqlUser = "select nome from usuariostsk where id = ";
	if($id!='0'):
		$criadopor = $Registro['criadopor'];
		$criadoem = $Registro['criadoem'];
		echo $Registro['alteradopor'];
		$alteradopor = empty($Registro['alteradopor'])?0:$Registro['alteradopor'];
	else:#id==0
		$criadopor = $_SESSION[$SIdUsuario];
		$criadoem = date('d/m/Y H:i');
		$alteradopor = 0;
	endif;
	$trcolspan="";
	if($colspan>0):
		$trcolspan='colspan="'.$colspan.'"';
	endif;
	$trA = '<tr>
	<td '.$trcolspan.' width="100%" height="60">';
	$trF="</td>
	</tr>";
	$CrPor = QueryBanco($sqlUser.$criadopor,0);
	$StrRetorno = " <div class='label-info informativo'> Criado por $CrPor em ". $criadoem." <br />";

	if($alteradopor>0):
		$AtPor = QueryBanco($sqlUser.$alteradopor,0);
		$StrRetorno .= "Alterado por $AtPor em ". $Registro['alteradoem'];
	else:
		$StrRetorno .= "<br/>";
	endif;
	$StrRetorno .= "</div>";
	echo $trA.$StrRetorno.$trF;
}
#FIM função que exibe os campos de auditoria na tela de cadastro/edicão somente se for edição

#INICIO função QueryStrings retorna registros de uma coluna apenas separados por virgula
function QueryStrings($sql,$separador=","){
	$QueryAux =  QueryOrDie($sql,"QueryStrings");
	$ArrAux = array();
	while($RsAux = mysqli_fetch_array($QueryAux)):
	array_push($ArrAux,$RsAux[0]);
	endwhile;
	return implode("$separador",$ArrAux);
}
#FIM função QueryStrings retorna registros de uma coluna apenas separados por virgula

#INICIO função QueryBanco se array=1, retornar o array todo, senão somente o campo 0
function QueryBanco($sql,$Array){
	global $conexao;
	#Debug($sql,0,0);
	$res = QueryOrDie($sql,"'QueryBanco'");
	$reg = mysqli_fetch_array($res);

	if($Array==1):
		$Array = array();
		$total = mysqli_num_rows($res);
		$Array = $reg;
	else:
		$Array=$reg[0];
	endif;
	return $Array;
}
#FIM função QueryBanco

#INICIO função ExibirTitCadEd para exibir o titulo corretamente quando cadastro ou edição de informações
function ExibirTitCadEd($texto,$par){
	global $local,$SAcademia;
	$Academia = 1;
	if(isset($_SESSION[$SAcademia])):
		$Academia = $_SESSION[$SAcademia];
	endif;
	$titulo_aux = "";#retorna nada
	$titaux="";
	#Debug($texto,0,0);
	if($local>1):#admin	
		if($texto=="Fluxo de Caixa"):
			$texto="$texto";
		else:
			$titaux = "TaskList -&raquo;";#retorna nome do site
			if($par!=""&&$par!="R"):#Já está logado e não é relatório
				$titulo_aux = " Cadastro de ";
				if($par=="L"):#Listagem
					$titulo_aux =  " Listagem de ";
				else:
					$titaux="";#remove o nome da academia da tela de cadastro/edição
	#Debug($par,0,0);
					if($par>0):#cadastro
						$titulo_aux = " Edição de ";
					endif;
				endif;
			endif;
		endif;#Fluxo de caixa
	endif;
#	return $par;
	return ucfirst($titaux).ucfirst($titulo_aux.$texto);
}
#FIM função ExibirTitCadEd

#INICIO função valida se logou/tem permissão na pagina
function ValidaAutenticacao(){
	#disponibilizando as variáveis globais
	global $SIdUsuario;
#echo $Perfil;
	if(!isset($_SESSION[$SIdUsuario])):
		$script='<script type="text/javascript" >
			alert("Usuário não autenticado!");
			window.location="../home/";
			</script>';
	endif;
}
#FIM função valida se logou/tem permissão na pagina

#INICIO funcão AlertOrLocation: para burlar o problema de header do php e redirecionar para página específica
function AlertOrLocation($msg,$pagina){
	$script = "<script type='text/javascript'>";
	if($msg!=""):
		$script .="alert('$msg');";
	endif;
	
	if($pagina!=""):
		$script .="window.location = '$pagina';";
	endif;
	$script .= "</script>";
	echo $script;
}
#FIM funcão AlertOrLocation

#INICIO funcão AlertAndClose: para fechar a janela que abriu apos salvar
function AlertAndClose($msg){
	$script = "<script type='text/javascript'>";
	if($msg!=""):
		$script .="alert('$msg');";
	endif;
	
	$script .="
	window.opener.location.reload();";
	#$script .="window.close();";
	$script .= "</script>";
	echo $script;
}
#FIM funcão AlertAndClose

#INICIO função ComboBanco
function ComboBanco($Nome,$sql,$sel='',$onchange='',$Rotulo='Escolha'){
	#disponibilizando as variáveis globais
	global $estilo, $disabled, $value, $return;
	$res = QueryOrDie($sql,"ComboBanco");
#echo $sql;
#exit;
	$ComboB = '<select ';
	$ComboB .= ' name="'.$Nome.'" id="'.$Nome.'" ';
	#só usa nome se houver um
	if(strlen($Nome)>3):
		#$ComboB .= ' name="'.$Nome.'" id="'.$Nome.'" ';
	endif;
	$ComboB .= $estilo;
	#só usa onchange se houver um
	if(strlen($onchange)>3):
		$ComboB .=' onchange="'.$onchange.';" ';
	endif;

	$ComboB .= $disabled."> 
<option value='$value'>$Rotulo</option>";
	while($reg=mysqli_fetch_array($res)):
		$ComboB .= "<option value='$reg[0]'";
		if($sel==$reg[0]):
			$ComboB .= " selected ";
		endif;
		$ComboB .= ">$reg[1]</option>";
	endwhile;
	$ComboB .='</select>';
	if($return):
		return $ComboB;
	else:
		echo $ComboB;
	endif;
}
#FIM função ComboBanco

#INICIO função ComboArray
function ComboArray($nomecampo,$Array,$sel,$onchange="",$Rotulo='Escolha'){
	#disponibilizando as variáveis globais
	global $estilo, $disabled, $value, $UsarItem, $return;
	#só usa onchange se houver um
	if(strlen($onchange)>3):
		$onchange =' onchange="'.$onchange.';"';
	endif;
	#print_r($Array);
	$combo = "<select name='$nomecampo' id='$nomecampo' $disabled $estilo $onchange>";
	if(strlen($value)>0):
		$combo .= "<option value='$value'>$Rotulo</option>";
	endif;
	foreach($Array as $i=>$item):
		if($UsarItem==1):
			$i=$item;
		endif;
		$combo .= "<option value='$i'";
		if($i==$sel):
			$combo .= "selected='selected'";
		endif;
		$combo .= ">$item</option>";
	 endforeach;
	$combo .= "</select>";

	if($return):
		return $combo;
	else:
		echo $combo;
	endif;
}
#FIM função ComboArray

#INICIO função ComboOrdemMenu
function ComboOrdemMenu($sel='0',$tabela,$area=0, $campo='ordem'){
	#disponibilizando as variáveis globais
	global $estilo;
	$sql = "select count(*) as total from $tabela ";
	if($tabela=="areas_do_sistema"):
		$sql .= "where aninhado_em=$area";
	endif;
	$total = QueryBanco($sql,0);
#		echo $sql;exit;
	$ComboO ='<select name="'.$campo.'" '.$estilo.'  >';
	for($i=1;$i<=$total;$i++):
		$ComboO .= "<option value='$i'";
		if($sel==$i):
			$ComboO .= " selected ";		
		endif;
		$ComboO .= ">$i</option>";
	endfor;
	$ComboO .='</select>';
	echo $ComboO;
}
#FIM função ComboOrdemMenu

#INICIO função ComboSubMenus
function ComboSubMenus($sel='',$thisid){
	#disponibilizando as variáveis globais
	global $estilo;
	#Não pode aninhar nele mesmo
	$sqlThisId = "";
	if($thisid!=0):

		$sqlThisId = " where id <> $thisid";
	endif;
	$sql = "select id, nome from areas_do_sistema $sqlThisId ";
	$res = QueryOrDie($sql,"'ComboSubMenus'");
#echo $sql;exit;
	$ComboS ='<select name="aninhado_em" '.$estilo.'>';
	$ComboS .= "<option value='0'>Principal</option>";
	while($reg=mysqli_fetch_array($res)):
		$ComboS .= "<option value='$reg[0]'";
		if($sel==$reg[0]):
			$ComboS .= " selected ";
		endif;
		$ComboS .= ">$reg[1]</option>";
	endwhile;
	$ComboS .='</select>';
	echo $ComboS;
}
#FIM função ComboSubMenus

#INICIO função AutoInsertUpdate
function AutoInsertUpdate($ArrayCampos,$tabela){
	#disponibilizando as variáveis globais
	global $conexao, $SIdUsuario,$SPerfil,$pagina,$ArquivoListagem,$External_ID,$local;
#Debug("Tabela: $tabela <br> Campos: ",0,0);
#Debug($ArrayCampos,1,0);
	$Internal_UserID = $_SESSION[$SIdUsuario];

#se é para inserir outro
	if(isset($ArrayCampos['__addPlus'])&&($ArrayCampos['__addPlus']=='X')):
		$msg="";
		$pagina = $ArquivoListagem;
	endif;

#se é para permanecer
	if(isset($ArrayCampos['__addPlus'])&&($ArrayCampos['__addPlus']=='S')):
		$msg="";
		$pagina = $ArquivoListagem."?id=".$ArrayCampos['id'];
	endif;
#Debug($ArquivoListagem,0,1);
	$ThisID = $ArrayCampos['id'];
	$ArrayCamposAux = $ArrayCampos;
	$ArrayCampos = array();
	
	$sql = "select * from $tabela where id=0";
#echo $sql;
	$res = QueryOrDie($sql,"Campos na tabela $tabela para insert/update");
	$total_campos = mysqli_num_fields($res);
	$fieldinfo=mysqli_fetch_fields($res);
	#for($i=0;$i<$total_campos;$i++):
	foreach ($fieldinfo as $val):
	#for($i=0;$i<$total_campos;$i++):#percorrendo campos da tabela
		$nome_campo = $val->name;
		foreach($ArrayCamposAux as $campo=>$conteudo):#percorrendo campos do array
			if($nome_campo==$campo):#validando campos da tabela com do array
				$ArrayCampos[$nome_campo] = $conteudo;#guardando no novo array
			endif;
		endforeach;
	endforeach;
	
	#se é efetivação de cadastro experimental, zera o ID, o ID original ainda está disponível na $ThisID
	if(isset($ArrayCamposAux['efetivar'])):
		$ArrayCampos['id'] = 0;
	endif;

	$campos = array();
	$values = array();
	if(isset($ArrayCampos['senha'])&&($ArrayCampos['senha']!='')):
		$ArrayCampos['senha']=md5($ArrayCampos['senha']);
	endif;

	foreach($ArrayCampos as $campo=>$conteudo):
		$conteudo = addslashes($conteudo);#aspas
		if($ArrayCampos['id']!='0'):
			if($campo!='id'&&($conteudo!=''||$campo!='senha')):
				array_push($campos, "$campo='$conteudo'");
			endif;
		else:
			if($campo!='id'):
	#echo "Campo: $campo , Conteudo: $conteudo<br />";
				array_push($campos,$campo);
				array_push($values, "'$conteudo'");
			endif;
		endif;
	endforeach;

	if($ArrayCampos['id']!='0'):
		$sql = "update $tabela set ";
		$sql .= implode(",",$campos);
		if($tabela!="comentarios"&&$tabela!="emails_recebidos"):
			$sql .= ",alteradopor = ".$Internal_UserID;
			$sql .= ",alteradoem = '".date('Y-m-d H:i:s')."'";
		endif;
		$sql .= " where id='".$ArrayCampos['id']."'";
		$msg = "Dados atualizados!!";
	else:
		$sql = "insert into $tabela (";
		$sql .=  implode(",",$campos);
		if($tabela!="comentarios"&&$tabela!="emails_recebidos"):
			$sql .= ",criadoem,criadopor, alteradoem, alteradopor";
		endif;
		$sql .= ") values (";
		$sql .=  implode(",",$values);
		if($tabela!="comentarios"):
			$sql .= ",'".date('Y-m-d H:i:s')."',".$Internal_UserID;
			$sql .= ",'".date('Y-m-d H:i:s')."',".$Internal_UserID;
		endif;
		$sql .= ")";
		$msg = "Dados inseridos!!";
	endif;
#Debug("LOCAL:$local SQL:$sql",0,0);
	$aux=QueryOrDie($sql,$tabela);
	$External_ID = mysqli_insert_id($conexao);#para uso externo
}
#FIM função AutoInsertUpdate

#INICIO função executa query e retorna os dados/erro
function QueryOrDie($sql,$item){
	global $conexao;
	#echo $sql;
	$Aux_query = mysqli_query($conexao,$sql) or die("<br />Erro: $item:".mysqli_error($conexao)."<br /> Consulta executada:<span class='list-group-item-info'>$sql</span>");
	return $Aux_query;	
}
#FIM função executa query e retorna os dados/erro

#FIM  função para exibição de Histórico de POSTS
function DataExtenso($data,$formato){
	setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' );
	date_default_timezone_set( 'America/Sao_Paulo' );
	echo strftime( $formato, strtotime($data ) );	
}

#INICIO identificacao navegador
function Navegador(){
 $useragent = $_SERVER['HTTP_USER_AGENT'];
 #$matched =  array('MSIE', 'Firefox', 'Chrome', 'Safari');
  if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'IE';
  } elseif (preg_match( '|Opera/([0-9].[0-9]{1,2})|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Opera';
  } elseif(preg_match('|Firefox/([0-9\.]+)|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Firefox';
  } elseif(preg_match('|Chrome/([0-9\.]+)|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Chrome';
  } elseif(preg_match('|Safari/([0-9\.]+)|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Safari';
  } else {
    // browser not recognized!
    $browser_version = 0;
    $browser= 'other';
  }
#  echo "browser: $browser $browser_version";
  return $browser;
}
#FIM identificacao navegador

#INICIO incluir arquivos para datepicker
function CampoDatePicker($destino,$pickTime,$return=false){
	global $EnableWeenEnd;
	$codigo = '  ';
	$codigo .= '<script type="text/javascript">';
	$codigo .="
	 $('#".$destino."').datetimepicker({
		 ".$pickTime."
   		language:  'pt-BR',
		weekStart: 0,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		pickerPosition: 'bottom-left',
		forceParse: 0
    });";
	if(!$EnableWeenEnd):
		$codigo .="
		$('#".$destino."').datetimepicker('setDaysOfWeekDisabled', [0,6]);";
	endif;
	$codigo .="
</script>";
	if($return):
		return $codigo;
	else:
		echo $codigo;
	endif;
}
#FIM incluir arquivos para datepicker

#INICIO função testa10
function testa10($valorinicial){
	$valor = (int)$valorinicial;
	if($valor<10):
		$valor = "0".$valor;
	endif;
	return $valor;
}
#FIM função testa10

#INICIO função testa100
function testa100($valorinicial){
	$valor = (int)$valorinicial;
	if($valor<10):
		$valor = "0".$valor;
	endif;
	if($valor<100):
		$valor = "0".$valor;
	endif;
	return $valor;
}
#FIM função testa100

#INICIO função TestaZeros
function TestaZeros($valorinicial,$zeros){
	$valor = (int)$valorinicial;
	for($i=1;$i<$zeros;$i++):
		if(strlen($valor)<$zeros):
			$valor = "0".$valor;
		endif;
	endfor;
	return $valor;
}
#FIM função TestaZeros

#INICIO função testadez
function testadez($valorinicial){
	$valor = (int)$valorinicial;
	if($valor<10):
		$valor = "0".$valor;
	endif;
	return $valor;
}
#FIM função testadez

#INICIO função testacem
function testacem($valorinicial){
	$valor = (int)$valorinicial;
	if($valor<10):
		$valor = "0".$valor;
	endif;
	if($valor<100):
		$valor = "0".$valor;
	endif;
	return $valor;
}
#FIM função testacem


#INICIO funcão MontaFiltroCampos
function MontaFiltroCampos($ArrCampos,$tabela){
	global $Alias, $palavra, $ArquivoListagem;
#echo "$ArquivoListagem "; exit;
#Debug($ArrCampos,1,0);

	$sqlRetorno="";
	$palavra = empty($ArrCampos['palavra'])?"":$ArrCampos['palavra'];
#print_r($ArrCampos);exit;
	if(isset($ArrCampos['busca'])&&strlen($ArrCampos['busca'])>0):
		$ArrCampos['pagina'] = 1;#evitar problema na paginação
		$busca =  $ArrCampos['busca'];

#Debug( "=> palavra: $palavra - / tabela: $tabela busca: $busca",0,0);
		if(strlen($busca)>0):
#echo "Palavra $palavra  ".$ArrCampos['busca'];
			#campos padrão para todas as tabelas
			if(strlen($palavra)>0):
				$sqlRetorno .= "  mst.id = '$palavra' ";
				
				$notlike = "";
				$conflike ="";
				switch(strtolower($tabela)):
					case 'tasklisttsk':
							$sqlRetorno .= " OR mst.titulo LIKE '%$palavra%'";
							$sqlRetorno .= " OR mst.descricao LIKE '%$palavra%'";
							$sqlRetorno .= " OR usu.nome LIKE '%$palavra%'";
					break;
					case 'usuariostsk':
							$sqlRetorno .= " OR per.nome LIKE '%$palavra%'";
							$sqlRetorno .= " OR mst.codigo LIKE '%$palavra%'";
					break;
					case '':
						$sqlRetorno .= "  ";
					break;
				endswitch;
			endif;#palavra
#echo $sqlRetorno ; exit;
			if(strlen($sqlRetorno)>0):
				$sqlRetorno = "($sqlRetorno) AND ";
			endif;
		endif;#strlen palavra
#Debug( "=> palavra: $palavra - / tabela: $tabela busca: $busca". isset($ArrCampos['filtropor']),0,0);
	endif;#isset busca
#Debug($sqlRetorno,0,1);
	$BuscarAtivo = "";
#Trará todos
	$StatusAtivo = "0,1,2";
	if(isset($ArrCampos['inativo'])):
		$StatusAtivo = $ArrCampos['inativo'];
	endif;
	
#Debug("StatusAtivo: $StatusAtivo",0,0);
#Busca padrão
	$BuscarAtivo = "  ".$Alias."ativo in (".$StatusAtivo.") ";
		

	if($tabela==""):#se não tem tabela, não usa filtro
		$BuscarAtivo="";
	endif;

	if($BuscarAtivo!=""):
		$sqlRetorno .= "  $BuscarAtivo ";
	endif;
#Debug($ArrCampos['inativo'].$ArquivoListagem.$sqlRetorno,0,0);

	return $sqlRetorno;
	
}
#FIM funcão MontaFiltroCampos

#prevenindo SQL Injection
function Sanitizar($conteudo){
	$conteudo = strip_tags($conteudo);
	$conteudo = htmlspecialchars($conteudo);
	$conteudo = trim(rtrim(ltrim($conteudo)));
	$conteudo = addslashes($conteudo);
	return $conteudo;
}

#INICIO função validar login
function ValidaLogin($arrLogin){
	global $SIdUsuario, $SNomeUsuario, $SDtUltLogon, $SAreadestino;
	$msg ="";
	$retorno = "../../sistema/home/?";
	
	#Sanitizando para bloquear sql injection
	$usuario = Sanitizar($arrLogin['email']);
	$senha = Sanitizar($arrLogin['senha']);
		
	$sql = "SELECT 
			usu.id, 
			usu.nome,
			DATE_FORMAT(usu.dt_ult_logon ,'%d/%m/%Y %H:%i:%s') as ultimologon
			FROM usuariostsk usu 
			 WHERE  usu.ativo     = 1 
			 and usu.email = '$usuario' AND usu.senha = MD5('$senha')";
#Debug($sql,0,0);
	$RecSet = QueryOrDie($sql,'Erro na autenticação!');
	$totalLinhas = mysqli_num_rows ( $RecSet );
#Debug($sql,0,0);
	if ($totalLinhas == 1):#só pode retornar UM usuário com essa senha, evitando outro tipo de SQL Injection
		$AcessoOK = true;
		$arrInfoUser =  mysqli_fetch_array($RecSet);#retrona o array de campos da consulta
		$_SESSION[$SIdUsuario]      = $arrInfoUser['id'];
		$_SESSION[$SNomeUsuario]    = $arrInfoUser['nome'];
		$_SESSION[$SDtUltLogon]     = $arrInfoUser['ultimologon'];

	#echo "USER ID:".$_SESSION[$SIdUsuario];exit;
			$areadestino = "../tarefas/index.php";

			$_SESSION[$SAreadestino] = $areadestino;
#Debug($areadestino,0,1);
			#atualizando ultimo logon (hoje)
			$sql ="update usuariostsk set dt_ult_logon=NOW() where id=".$arrInfoUser['id'];
	#echo $sql; exit; 
			QueryOrDie($sql ,'Atualizar ultimo logon!');
	else:
		$msg="Usuário ou senha Inválidos!!";
		AlertOrLocation($msg,$retorno);#redireciona para a página de login
	endif;
}
#FIM função validar login

#INICIO funcao que retorna o nome do aluno
function NomeUser($aluno){
	$queryAluno = "Select mst.nome from usuariostsk mst where mst.id = '".$aluno."' or mst.codigo='".$aluno."'";
	return QueryBanco($queryAluno,0);
}

#Função que gera senha aleatoria
function GeraSenha($length=9, $strength=0) {
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength & 2) {
		$vowels .= "AEUY";
	}
	if ($strength & 4) {
		$consonants .= '23456789';
	}
	if ($strength & 8) {
		$consonants .= '@#$%';
	}
 
	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}
#FIM função gera senha aleatória

#INICIO Verifica codigo, nome e email duplicado
function VerificaDuplicado($ArrCampos){
	$msg = "";
	
	if(isset($ArrCampos['email'])):
#Debug($ArrCampos,1,0);
		#verificando se o email já está cadastrado
		$sql = "select nome from usuariostsk mst where mst.email='".$ArrCampos['email']."'";
		$nome = QueryBanco($sql,0);
	#Debug($sql,0,0);
		if(strlen($nome)>0):#no novo cadastro, não pode haver nenhum
			$msg = "O email <strong>".$ArrCampos['email']."</strong> já está em uso para o aluno <strong>".$nome."</strong>!";
		endif;

	endif;

	echo $msg;
}
#FIM Verifica codigo, nome e email duplicado

#INICIO ExcluirRegistro
function ExcluirRegistro($ArrData){
	global $SIdUsuario;
	#recuperando variáveis
	$tabela = $ArrData['tabela']; 
	$msg = ucfirst($ArrData['item']).' excluído(a)!';
	$ArrDados['id'] = $ArrData['id'];
	$ArrDados['ativo'] = 0;#inativa também
	$ArrDados['excluidoem'] = date('Y-m-d H:i');
	$ArrDados['excluidopor'] = $_SESSION[$SIdUsuario];
	#atualizando o registro
	AutoInsertUpdate($ArrDados,$tabela);

	echo $msg;
}
#FIM ExcluirRegistro

#INICIO Busca parametros da tela
function GetWidthHeigh($tabela){
	$ArrRet = array();
	$ArrRet['dialog_w']="60%";
	$ArrRet['dialog_h']="550";
	return $ArrRet;
}
#FIM Busca todos os itens da tela

function AjustaTitulo($titulo){
	#ajuste do título para as caixas de diálogo
	$ArrTit = explode("-&raquo;",$titulo); 
	#Debug($ArrTit,1,0);
	#Debug($ArrTit[1],0,0);
	
	if(isset($ArrTit[1])):
		$Tit = $ArrTit[1];
	else:
		$Tit = $ArrTit[0];
	endif;
	$Tit = str_replace("Listagem de ","",$Tit);

	return $Tit;
}

function OpenFormDialog($tabela,$OpenForm,$tituloExibe){
	global $arqEd,$SAcademia;
	$ArrScrItens = GetWidthHeigh($tabela);
		#Script de open dialog
		#Debug($arqEd,0,1);
		$script = '<script type="text/javascript">
$( function() {
/* variaveis pra controle do form/dialog*/
	var record_w_V = "'.$ArrScrItens['dialog_w'].'", 
	record_h_V = '.$ArrScrItens['dialog_h'].', 
	new_label_V = "Cadastro de ",
	updt_label_V = "Edição de ",
	screen_name = "'.$tituloExibe.'",
	inactivate_msg_V = "Desativar",
	tabela = "'.$tabela.'";

//Controlar as caixas de diálogo quando abrir no celular
	var Larg = $(document).width();
	if(Larg<550){
		record_w_V="95%";
	}
	
	var dialog = $("#dialog_form").dialog({
      autoOpen: false,
      width: record_w_V,
      height: record_h_V,
      modal: true
    });
 	
	
    $( "#Adicionar" ).click(function() {
		var IdVal = $(this).val();
		//alert("Teste");
		OpenDialog(IdVal,"'.$arqEd.'");
    });
	
	';
	$script .= '
    $( ".open-form" ).click(function() {
		var IdVal = $(this).val();
		//alert(IdVal);
		OpenDialog(IdVal,"'.$arqEd.'");
    });
	';
	$script .= '
	$( ".inactivate" ).click(function() {
		var BtId = $(this).val();
		var Arr = BtId.split("#");
		confirm_desativar(Arr[0],Arr[1],Arr[2],inactivate_msg_V,tabela);
    });
	
	function OpenDialog(IdVal,url){
		var record_h = record_h_V;
		if(IdVal!="0"){
			titulo = updt_label_V+screen_name;
			titulo = titulo+" ["+IdVal+"]";
		}else{
			titulo = new_label_V+screen_name;
			record_h -=50;
		}

		url += "id="+IdVal;
		$("#dialog_form").dialog( "option", "title",  titulo);
		$("#dialog_form").dialog( "option", "width",  record_w_V);
		$("#dialog_form").dialog( "option", "height",  record_h);
		url = Crc(url);
		$("#content_form").load(url, function(responseTxt, statusTxt, xhr){
			if(statusTxt == "success"){
				//oculta os erros se houverem
				$( "#divError" ).hide();
				dialog.dialog("open");
			}
		});
	}//OpenDialog
	});//ONLOAD
	</script>
<div id="dialog_form" title="Titulo do formulario">
    <div id="divError" class="alert alert-dismissible alert-warning">
        <button type="button" id="closemsgerr" class="close">&times;</button>
	        <div id="msgerr">
            </div>
        </div>
		<div id="content_form">
			
	</div><!-- content_form -->
</div> <!-- dialog_form -->
';
	return $script;
}

#INICIO DebugTela
function Debug($Txt,$Arr,$Sair){
	echo "<br><br><br>";
	if($Arr):
		print_r($Txt);
	else:
		echo $Txt;
	endif;
	if($Sair):
		exit;
	endif;
}
#FIM DebugTela
function RandomBodyBG($max){
	$id = rand(1, $max);
	echo '<style  type="text/css">
body{
	background-image: url(../../imagens/comuns/bg--intro--0'.$id.'.jpg);
}
</style>';
}

#INICIO retornar o próximo código de usuário
function ProxCodigoUsu(){
	
	$sql = "SELECT IFNULL(max(mst.codigo)+1,1) as ultimo from usuariostsk mst";
#TelaDebug($sql,0,1);
	$codigo = QueryBanco($sql,0);
	echo TestaZeros($codigo,4);
}
#FIM  retornar o próximo código na sequencia

#INICIO TrocaSituacao
function TrocaSituacao($ArrData){
	global $SPaginaLista,  $SIdUsuario;
	#echo $_SESSION[$SPaginaLista];exit;
	#recuperando variáveis
	$id = $ArrData['id'];
	$acao = $ArrData['acao'];
	$tabela = $ArrData['tabela']; 
	$exibir = $ArrData['exibir'];
	$campo = $ArrData['campo'];
	$data_hora = date('Y-m-d H:i');
	$sql1 = "update $tabela set $campo=$acao ";
	$sql2 = "";
	$sql3 = ", alteradoem ='". date('Y-m-d H:i:s')."', alteradopor = ".$_SESSION[$SIdUsuario];
	$cwhere=" where id = $id";	
	$msg = "Excluindo registro";
	#TelaDebug($sql1,0,1);
	if($campo=="status"):
		if($acao==1):
			$sql2 = ", concluidoem ='', concluidopor ='' ";
			$msg = "Tarefa reaberta!";
		else://Concluído
			$sql3 = ", concluidoem ='". date('Y-m-d H:i:s')."', concluidopor = ".$_SESSION[$SIdUsuario];
			$msg = "Tarefa concluída!";
		endif;
	else:
		if($acao==1):
			$msg = "Restaurando registro!";
		else:
			$sql2 = ", excluidoem ='". date('Y-m-d H:i:s')."', excluidopor = ".$_SESSION[$SIdUsuario];
			$sql2 = ", ativo =0";
		endif;
	endif;
	$sql = $sql1.$sql2.$sql3." $cwhere";
#Debug($sql,0,0);
	QueryOrDie($sql,$msg);

	if($msg!=""):
		echo $msg;
	endif;

}
#FIM  TrocaSituacao

?>