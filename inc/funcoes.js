Number.prototype.format = function(n, x, s, c) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
        num = this.toFixed(Math.max(0, ~~n));

    return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
};
 
$(document).ready(function() {
	//Teclas de atalho
	shortcut.add("F2",function() {
		$("#Adicionar").click();
	});

  $('[title]').hover(function(e){ 
  
        var texto = $(this).attr('title');
        var id = $(this).attr('id')

        $(this).data('data-texto', texto).removeAttr('title');
  		if(id!="dialog_form"){
        	$('<p class="dica"></p>').text(texto).appendTo('body').css('top', (e.pageY - 10) + 'px').css('left', (e.pageX + 20) + 'px').fadeIn('fast');
  		}
    },function(){
  
        $(this).attr('title', $(this).data('data-texto'));
        $('.dica').remove();
  
    }).mousemove(function(e){
  
        $('.dica').css('top', (e.pageY - 23) + 'px').css('left', (e.pageX + 21) + 'px'); 
    });
 
	$("#logo_site").click(function(){
		LinkSimples("index.php");
	});

		
	$( window ).resize(function() {
		/* Ajustando o container ao tamanho da janela*/
		AjustaConteudo();
	});
	

	$(".del_itens").click(function(){
		var v_itens = $(this).val(),ArrItens,//id#item#tabela
		selecio='selecionado';
		//alert(v_itens);
		ArrItens = v_itens.split("#");
		if(ArrItens[0]  !=""){
			if(ArrItens[1]=='matricula'){
				selecio = 'selecionada';
			}
			if(confirm("Excluir "+ArrItens[1]+" "+selecio+"?")){
				var campos = "id="+ArrItens[0]+"&item="+ArrItens[1]+"&tabela="+ArrItens[2];
				var url = "../../inc/ExcluirRegistro.php";
				//alert(url+"?"+campos);
				PostStatus(url,campos);
			}
		}
	});

	AjustaConteudo();
	
});//fim do $(document)

function SubmitFormDlay(FormParam){
setTimeout(function(){ 
				$("#"+FormParam).submit();
			}, 10);
}


var ArrCorHex = new Array(
	"#CCFFCC",
	"#ADFF2F",
	"#FFFF99",
	"#FFFF66",
	"#FFFF33",
	"#FFFF00",
	"#FDE910",
	"#FFDB58",
	"#FFCC00",
	"#FFCC33",
	"#FFCC66",
	"#FF9933",
	"#FF9900",
	"#FF8C00",
	"#FF0000");

function DataHoje(){
		var data = new Date();
		var dia     = data.getDate(); 
		var mes     = data.getMonth();
		mes++; 
		var ano    = data.getFullYear();
		if(dia<10){
			dia = '0'+dia;
		}
		if(mes<10){
			mes = '0'+mes;
		}
		var str_data = dia + '/' + mes + '/' + ano;
		return str_data;
}

function HoraAtual(){
		var data = new Date();
		var hora     = data.getHours(); 
		var minuto   = data.getMinutes();
		var segundos = data.getSeconds();
		var str_hora = hora + ':' + minuto+":"+segundos;
		return str_hora;
}

function DataOntem(){
		var data = new Date();
		data  = new Date(data.getFullYear(),data.getMonth(),data.getDate()-1);
		var dia     = data.getDate(); 
		var mes     = data.getMonth();
		mes++; 
		var ano    = data.getFullYear();
		if(dia<10){
			dia = '0'+dia;
		}
		if(mes<10){
			mes = '0'+mes;
		}
		var str_data = dia + '/' + mes + '/' + ano;
		return str_data;
}

function InicioMes(){
		var data = new Date();
		data  = new Date(data.getFullYear(),data.getMonth(),1);
		var dia     = data.getDate(); 
		var mes     = data.getMonth();
		mes++; 
		var ano    = data.getFullYear();
		if(dia<10){
			dia = '0'+dia;
		}
		if(mes<10){
			mes = '0'+mes;
		}
		var str_data = dia + '/' + mes + '/' + ano;
		return str_data;
}

function ProximoMes(){
		var data = new Date();
		data  = new Date(data.getFullYear(),data.getMonth()+1,data.getDate());
		var dia     = data.getDate(); 
		var mes     = data.getMonth(); 
		var ano    = data.getFullYear();
		mes++;//Corrigindo mês
		if(dia<10){
			dia = '0'+dia;
		}
		if(mes<10){
			mes = '0'+mes;
		}
		var str_data = dia + '/' + mes + '/' + ano;
		return str_data;
}

function converte_dataJS(data,de){

	if((data!='0000-00-00')&&(data.length>=10)){
		var separa="-";
		if(de=="/"){
			separa="-";
		}else{
			separa="/";
		}
		if(data.length==10){
			var dataN = data.split(de);
		}else{
			var dataH = data.split(" ");
			var dataN = dataH[0].split(de);
			dataN[0] += " "+dataH[1];
		}
	//print ($separa. $dataN); exit;
		return dataN[2]+separa+dataN[1]+separa+dataN[0];
	}else{
		return "";
	}
}


/* Ajustando o container ao tamanho da janela*/
function AjustaConteudo(){
	var alturaTopo  = $(".topo_geral").height();
	var alturaRodape  = $(".rodape").height();
	var alturaContainer  = $("#container").height();
	var alturaDocumento  =  $(document).height();
	var alturaTotal = alturaContainer + alturaTopo + alturaRodape;
	
	if(alturaDocumento>alturaTotal){
		var alturaNova = (alturaDocumento - alturaTotal)+alturaContainer+50;
		//alert("alturaDocumento"+alturaDocumento +"-"+ alturaTotal +"-"+ alturaNova);
		$("#container").css('height',alturaNova+'px');
	}
}


//INICIO gerenciamento de checkboxes de campos com qualquer valor
function CheckValue(Ocheck,local,ValorChkd,ValorUnChkd){
	var str = $("#"+local).val();
	if(Ocheck.checked){
		str = ValorChkd;
	 }else{
		str = ValorUnChkd;
	}
//	alert(str + " " + local);
	$("#"+local).val(str);//atualizando informações da tela
}

//INICIO gerenciamento de checkboxes de campos com S/N
function CheckSimNao(Ocheck,local){
	var str = $("#"+local).val();
	if(Ocheck.checked){
		str = "S";
	 }else{
		str = "N";
	}
//	alert(str + " " + local);
	$("#"+local).val(str);//atualizando informações da tela
}

//INICIO link em botão
function LinkSimples(destino){
//	alert(destino);
	window.location =  destino;
}


/* INICIO confirmar exclusao registro*/
function confirm_trocasituacao(registro,acao,exibir,tabela,campo)
{
	var objeto = "registro";
	var txtacao = "excluir";
	acao = parseFloat(acao);//convertendo em número
	if(tabela=="tasklisttsk"){
		objeto = " tarefa";
		txtacao = "abrir";
		if(acao==2){
			txtacao = "concluir";
		}
	}else{
		if(acao==1){
			txtacao = "restaurar";
		}
	}
	var ShowAcao =  objeto+" "+registro+" - "+exibir;
	ShowAcao = txtacao+ShowAcao; 
//	alert(registro);
    var resposta=confirm("Confirma "+ShowAcao+"?")

    if (resposta) {
		var campos = "id="+registro+"&tabela="+tabela+"&acao="+acao+"&exibir="+exibir+"&campo="+campo;
		var url = "../../inc/Trocasituacao.php";
		//alert(url+"?"+campos);
		PostStatus(url,campos);
    }//if resposta
}
/* FIM confirmar exclusao registro*/

//INICIO post genérico 
function PostStatus(url,campos){
	$.post(url, campos , function(result){		
			if(result.length>4){
				alert(result);
			}

		}).done(function() {
			window.location.reload();
		});
}
//FIM post genérico 

//INICIO exibir ocultar div específica:nome da div , 'none' ou 'block'
function exibir(qualdiv){
	$("#"+qualdiv).slideToggle();
}

//INICIO exibir ocultar div DasMsgs com overlay
function ToggleDiv(qualdiv){
	$("#"+qualdiv).toggle();
}



//INICIO gerenciamento de formulários
function CarregaComboOrdem(local,area,ordem){
	var pagina = Crc("../../inc/CarregaComboOrdem.php?area="+area+"&ordenar="+ordem);
	//alert(pagina+"-"+local);
	$("#"+local).load(pagina);
}
//FIM gerenciamento de formulários


//----------- FUNCOES JQUERY/FORM -------------------
//INICIO gerenciamento de formulários
function GerenciaForm(formulario,pagina,local){
	var serializeDados = $('#'+formulario).serialize();
	pagina = Crc(pagina+".php?"+serializeDados);
//alert(pagina);
	$("#"+local).load(pagina);
}
//FIM gerenciamento de formulários

//INICIO crc
function Crc(pagina){
	var d = new Date();
	var crc=d.getTime();
	pagina=pagina.replace('?',"?crc="+crc+"&");
	return pagina;	
}
//FIM crc


/*----------------------- desenv 2016 -----------------------------*/

// Common functions
function pad(number, length) {
    var str = '' + number;
    while (str.length < length) {str = '0' + str;}
    return str;
}
function formatTime(time) {
    var min = parseInt(time / 6000),
        sec = parseInt(time / 100) - (min * 60)/*,
        hundredths = pad(time - (sec * 100) - (min * 6000), 2)*/;
    //return (min > 0 ? pad(min, 2) : "00") + ":" + pad(sec, 2) + ":" + hundredths;
	return (min > 0 ? pad(min, 2) : "00") + ":" + pad(sec, 2);
}

function DisplaySub(Ocheck){
	var valor = Ocheck.value;
	var Class = ".mnuI"+valor;
	$(Class).css('color','#000000');
//alert(Class);
	if(Ocheck.checked){
		 //$(Class).show();//Exibe
		 $(Class).prop('disabled',false);//Habilita
		 $(Class).css('color','#000000');//Habilita
	}else{
		//$(Class).hide();//Oculta
		$(Class).prop('disabled',true);//Desabilita
		$(Class).css('color','#FFFFFF');//Desabilita
	}
//alert($(Class).css('color'));
}

/*----------------------- desenv 03/2017 -----------------------------*/
/* Ajustando numeros digitados trocando virgulas 99,99 por ponto 99.99*/
function NumeroPontoVirgula(valor){
	valor = valor.replace('.','#');
	valor = valor.replace(',','.');
	valor = valor.replace('#','');
	return valor;
}
/* Ajustando numeros digitados trocando ponto 99.99 por virgulas  99,99*/
function NumeroVirgulaPonto(valor){
	var valor = valor.toString();
	valor = valor.replace('.',',');
	//alert(valor);
	return valor;
}

function TestaDez(valor){
		var Aux = parseInt(valor);
		if(Aux<10){
			Aux = '0'+valor;
		}
		
		return Aux;
}