<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Api\Boa\Logar;
use Api\Boa\Check;
use Api\Boa\Consultar;
use Api\Boa\utils\Util;
use Api\Boa\Filtro;

require_once 'vendor/autoload.php';

$logar = new Logar();

/*

php consultar.php  u=20007128864 p=200.178.251.146:8080 c="JSESSIONID=0000Fi...." > debug.html

*/

$usuario = null;
$proxy   = null;
$cookie  = null;

if(count($argv) > 1) {
	foreach($argv as $arv){
		if(stristr($arv, 'p=')){
			$proxy = str_replace('p=', '', $arv);
		}elseif(stristr($arv, 'u=')){
			$usuario = str_replace('u=', '', $arv);
		}elseif(stristr($arv, 'c=')){
			$cookie = str_replace('c=', '', $arv);
		}elseif(stristr($arv, 'd=')){
			$doc = str_replace('d=', '', $arv);
		}
	}
}else{
	//die('falta parametros...');
}

if($usuario != null || $proxy != null || $cookie != null || $doc != null) {

	$consult = new Consultar();
	$consult->setcookie($cookie);
	$consult->setProxy($proxy);
	$consult->setCpf($doc);

	$run = $consult->run();
	if($run === false) {
		$result = ['msg'=> 'erro ao consultar, cookie invalido', 'status' => false];
	}elseif($run === true) {
		$result = ['msg'=> 'erro ao consultar, cookie ok !', 'status' => true];
	}elseif(stristr($run, 'ES CONFIDENCIAIS')) {
		$limpa = new Filtro();
		$res = $limpa->json($run);
		$result = ['msg'=> 'ok', 'status' => true, 'dados' => $res];
	}elseif($run == 'rede'){
		$result = ['msg'=> 'rede ruim', 'status' => false];
	}
}else{
	$result = ['msg'=> 'nada a fazer ', 'status' => true];
}

echo json_encode($result);
