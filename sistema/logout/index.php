<?php session_start();
include('../../config/config_ini.php');#a referência é feita a partir da pagina onde este arquivo(cabecalho.php) será incluido
include('../../inc/funcoes.php');
session_destroy();
#header('Location: ../home/index.php');
AlertOrLocation('','../home/index.php');?>