<?php $titulo = " Login";
$Validar = 0;# NÃO deve validar o acesso/permissões
$par = "";
$local = 2;#admin?>
<?php include ('../../inc/cabecalho.php'); ?>
<link href="../../inc/signin.css" rel="stylesheet"/>
<?php 

#Autenticação de usuário
if(isset($_REQUEST['action']))://clicou no botao
	if($_REQUEST['action']=='entrar')://clicou no botao
		 ValidaLogin($_REQUEST,$local);
	endif;
endif;#action

if(isset($_SESSION[$SAreadestino]))://já logou antes
	AlertOrLocation("",$_SESSION[$SAreadestino]);
endif;#action
#Inicializando
$value = "Entrar";

#random da imagem de fundo
RandomBodyBG(4);
?>

<script type="text/javascript">
jQuery(function($){
	$('.container').height($(document).height());
});
</script>
   <div class="container">
   
   <div id="boasvindas">
    <b>Bem Vindo</b> ao controle de tarefas.
   </div>
       <form id="loginform"  name="loginform"  class="form-signin" role="form"method="post" action="">
       
		<h2 class="form-signin-heading"><?php echo $titulo; ?></h2>
			<input name="email" type="text" class="form-control" placeholder="Usuario" required autofocus/>
			<?php if($value=="Entrar"): ?><br />

            <input id="senha" name="senha" type="password" class="form-control" placeholder="Senha" required />
            <?php endif;?>
			<!--label class="checkbox"> 
			  <input type="checkbox" name="lembrar" value="remember-me"> Lembrar de mim
			</label-->
			  <button class="form-control btn-block" type="submit" name="action" value="<?php echo strtolower($value); ?>">
              <span class="glyphicon glyphicon-user"></span>
				<?php echo $value; ?>
            </button>
			</form>

<?php include('../../inc/rodape.php')?>