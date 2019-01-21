<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Api\Boa\Cnpj\Logar as cnpjLogar;
use Api\Boa\Cnpj\Consulta as cnpjConsulta;

use Api\Boa\Cpf\Logar as cpfLogar;
use Api\Boa\Cpf\Consulta as cpfConsulta;


require_once 'vendor/autoload.php';


/*
-u=20007064572 -s=7656 -p=138.255.165.86:50095 -t=cpf
*/

$usuario = null;
$senha   = null;
$proxy   = null;

if(count($argv) > 1) {
	foreach($argv as $arv){
		if(stristr($arv, '-u=')){
			$usuario = str_replace('-u=', '', $arv);
		}elseif(stristr($arv, '-s=')){
			$senha = str_replace('-s=', '', $arv);
		}elseif(stristr($arv, '-p=')){
			$proxy = str_replace('-p=', '', $arv);
		}elseif(stristr($arv, '-t=')){
			$tipo = str_replace('-t=', '', $arv);
		}
	}
}else{
	die('falta parametros...');
}

if(!isset($tipo)) {
	$tipo = 'cpf';
}


if($usuario != null && $senha != null && $proxy != null){

	if($tipo == 'cnpj') {

		$Logar = new cnpjLogar();
		$Logar->setProxy($proxy);
		$Logar->setUsuario($usuario);
		$Logar->setSenha($senha);
		$prlogin = $Logar->preLogin();

		if(is_array($prlogin)) {
			
			$Logar->setCookie($prlogin['cookie']);
			$Logar->setEcs($prlogin['ecs']);
			$Logar->setEncript($prlogin['encript']);

			$cookie = $Logar->logar();
		}else{
			$cookie = false;
		}

	}elseif($tipo == 'cpf') {

		$Logar = new cpfLogar();
		$Logar->setProxy($proxy);
		$Logar->setUsuario($usuario);
		$Logar->setSenha($senha);
		$prlogin = $Logar->preLogin();

		if(is_array($prlogin)) {
			
			$Logar->setCookie($prlogin['cookie']);
			$Logar->setEcs($prlogin['ecs']);
			$Logar->setEncript($prlogin['encript']);

			$cookie = $Logar->logar();
		}else{
			$cookie = false;
		}

	}

	if($cookie == 'rede'){
		echo "start=proxyoff::{$usuario}::{$proxy}::{$tipo}=end";
	}elseif($cookie == 'invalida'){
		echo "start=contaoff::{$usuario}::{$proxy}::{$tipo}=end";
	}
	elseif(strlen($cookie) > 15) {
		echo "start={$cookie}::{$usuario}::{$proxy}::{$tipo}=end";
	}else{
		echo "start=false::{$usuario}::{$proxy}::{$tipo}=end";	
	}
}else{
	echo "=nada a fazer=";
}
