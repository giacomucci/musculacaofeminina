<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('tabela_correios')};
CREATE TABLE {$this->getTable('tabela_correios')} (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `servico` VARCHAR(50),
  `nome` VARCHAR(50),
  `regiao` VARCHAR(150) NOT NULL,
  `prazo` INTEGER,
  `peso` DECIMAL(8,4),
  `valor` DECIMAL(8,2),
  `cep_origem` VARCHAR(50),
  `cep_destino_ini` INTEGER,
  `cep_destino_fim` INTEGER,
  `lastupdate` DATETIME,
  `cep_destino_ref` INTEGER,
  PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();