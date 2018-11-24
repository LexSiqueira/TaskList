</div><!-- /conteudo -->
<?php
if($local==2):#admin
	$titulo = strtoupper($titulo);
	if(isset($_SESSION[$SIdUsuario]))://tem session para este item, é sistema
		$ArrNome = explode(" ",$_SESSION[$SNomeUsuario]);
		echo '<div class="userinfo">Logado como: '.$ArrNome[0] .' [Último logon:'.$_SESSION[$SDtUltLogon].']</div >';
	endif;
	echo '<div class="rodape"><a href="https://www.linkedin.com/in/lex-siqueira-9b43a1115" target="_blank">By Lex Siqueira</a></div >';
endif;
mysqli_close($conexao);
?>
</body>
</html>