<?php

use Api\Boa\Cnpj\Check as cnpjCheck;
use Api\Boa\Cpf\Check as cpfCheck;

require_once 'vendor/autoload.php';

/*

php check.php -u=usuario -p=138.255.165.86:50095 -c="cookie..........." -t=cpf

*/

if(count($argv) > 1) {
	foreach($argv as $arv){
		if(stristr($arv, '-p=')){
			$proxy = str_replace('-p=', '', $arv);
		}elseif(stristr($arv, '-u=')){
			$usuario = str_replace('-u=', '', $arv);
		}elseif(stristr($arv, '-c=')){
			$cookie = str_replace('-c=', '', $arv);
		}elseif(stristr($arv, '-t=')){
			$tipo = str_replace('-t=', '', $arv);
		}
	}
}else{
	//die('falta parametros...');
}

if($usuario != null || $proxy != null || $cookie != null) {

	if($tipo == 'cpf') {
	
		$cpfcon = new cpfCheck();
		$cpfcon->setCookie($cookie);
		$cpfcon->setProxy($proxy);
		$run = $cpfcon->check();
	
	}elseif($tipo == 'cnpj') {

		$cnpjcon = new cnpjCheck();
		$cnpjcon->setCookie($cookie);
		$cnpjcon->setProxy($proxy);
		$run = $cnpjcon->check();
	
	}else{
		die('tipo nao encontrado =/');
	}

	if($run == true) {
		echo "start=cookieon::{$usuario}::{$proxy}::{$tipo}=end";
	}elseif($run == 'rede') {
		echo "start=redeoff::{$usuario}::{$proxy}::{$tipo}=end";
	}else {
		echo "start=cookieoff::{$usuario}::{$proxy}::{$tipo}=end";
	}
}else{
	echo "=nada a fazer=";
}
