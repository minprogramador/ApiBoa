<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use Api\Boa\Logar;
use Api\Boa\Check;
use Api\Boa\Consultar;
use Api\Boa\utils\Util;

require_once 'vendor/autoload.php';

$logar = new Logar();

/*
u=20007064572 s=7656 p=138.255.165.86:50095
*/

$usuario = null;
$senha   = null;
$proxy   = null;

if(count($argv) > 1) {
	foreach($argv as $arv){
		if(stristr($arv, 'u=')){
			$usuario = str_replace('u=', '', $arv);
		}elseif(stristr($arv, 's=')){
			$senha = str_replace('s=', '', $arv);
		}elseif(stristr($arv, 'p=')){
			$proxy = str_replace('p=', '', $arv);
		}elseif(stristr($arv, 't=')){
			$tipo = str_replace('t=', '', $arv);
		}
	}
}else{
	//die('falta parametros...');
}

if(!isset($tipo)) {
	$tipo = 'cpf';
}

if($usuario != null && $senha != null && $proxy != null){

	$logar->setProxy($proxy);
	$logar->setUsuario($usuario);
	$logar->setSenha($senha);

	$prlogin = $logar->preLogin();

	if(is_array($prlogin)) {
		
		$cookie  = $prlogin['cookie'];
		$ecs     = $prlogin['ecs'];
		$encript = $prlogin['encript'];

		if($tipo == 'cpf') {
			$cookie = $logar->runCpf($cookie, $ecs, $encript);
		}elseif($tipo == 'cnpj') {



		}else{
			echo 'nenhum tipo informado =/';
			die;
		}

		echo $cookie;


	}else{
		echo "nao tem array\n";
		print_r($prlogin);
	}
	echo "\n";
	die;

	$cookie = $logar->run();

	if($cookie == 'rede'){
		echo "start=proxyoff::{$usuario}::{$proxy}=end";
	}elseif($cookie == 'invalida'){
		echo "start=contaoff::{$usuario}::{$proxy}=end";
	}
	elseif(strlen($cookie) > 15) {
		echo "start={$cookie}::{$usuario}::{$proxy}=end";
	}else{
		echo "start=false::{$usuario}::{$proxy}=end";	
	}
}else{
	echo "=nada a fazer=";
}
