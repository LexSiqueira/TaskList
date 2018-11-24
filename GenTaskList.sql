CREATE DATABASE `cristianos_TaskList` /*!40100 COLLATE 'utf8_unicode_ci' */;
create user crist_utasklist identified by "yrc8N&87";


-- Dumping structure for table areas_do_sistema
DROP TABLE IF EXISTS `areas_do_sistema`;
CREATE TABLE IF NOT EXISTS `areas_do_sistema` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `nome` varchar(40) DEFAULT NULL,
  `caminho` varchar(40) DEFAULT NULL,
  `aninhado_em` int(5) NOT NULL DEFAULT '0',
  `ordem` int(5) NOT NULL DEFAULT '0',
  `criadoem` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `criadopor` int(30) NOT NULL,
  `alteradoem` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `alteradopor` int(30) DEFAULT NULL,
  `ativo` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- Dumping data for table areas_do_sistema: 5 rows
DELETE FROM `areas_do_sistema`;
INSERT INTO `areas_do_sistema` (`id`, `nome`, `caminho`, `aninhado_em`, `ordem`,  `criadoem`, `criadopor`) VALUES
	(1, 'Cadastros'		  , ''				  , 0, 2,  NOW(),  1),
	(2, 'Áreas do Sistema', 'areas_do_sistema', 1, 2, NOW(),  1),
	(3, 'Ações'		 	  , ''	 			  , 0, 1, NOW(),  1),
	(4, 'Tarefas'		  , 'tarefas' 		  , 3, 1, NOW(),  1),
	(5, 'Usuários'		  , 'usuarios'		  , 1, 1,NOW(),  1);


-- Dumping structure for table usuariostsk
DROP TABLE IF EXISTS `usuariostsk`;
CREATE TABLE IF NOT EXISTS `usuariostsk` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL DEFAULT '0',
  `nome` varchar(80) NOT NULL,
  `email` varchar(50) NOT NULL,
  `senha` varchar(35) NOT NULL,
  `dt_ult_logon` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `observacao` text NOT NULL,
  `criadoem` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `criadopor` int(30) NOT NULL,
  `alteradoem` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `alteradopor` int(30) NOT NULL,
  `excluidoem` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `excluidopor` int(30) NOT NULL,
  `ativo` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- Dumping data for table usuariostsk: 2 rows
DELETE FROM `usuariostsk`;
INSERT INTO `usuariostsk` (`id`, `codigo`, `nome`,  `email`, `senha`, `criadoem`, `criadopor`) VALUES
	(1, '00001', 'Alexsandro Siqueira', 'lexsiqueira@gmail.com', MD5('9196'), NOW(), 1 ),
	(2, '00002', 'Benjamin Siqueira', 'bensiqueira@gmail.com', MD5('9996'), NOW(), 1 );
	
	
-- Dumping structure for table tasklisttsk
DROP TABLE IF EXISTS `tasklisttsk`;
CREATE TABLE IF NOT EXISTS `tasklisttsk` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(80) NOT NULL,
  `descricao` text NOT NULL,
  `status` int(1) DEFAULT '1' COMMENT '1-Aberto, 2-Concluido',
  `criadoem` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `criadopor` int(30) NOT NULL,
  `alteradoem` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `alteradopor` int(30) NOT NULL,
  `concluidoem` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `concluidopor` int(30) NOT NULL,
  `excluidoem` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `excluidopor` int(30) NOT NULL DEFAULT '0',
  `ativo` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


