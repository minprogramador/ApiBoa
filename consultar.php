<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use Api\Boa\Cnpj\Consulta as cnpjConsulta;
use Api\Boa\Cpf\Consulta as cpfConsulta;

use Api\Boa\Filtro;

require_once 'vendor/autoload.php';

/*

php consultar.php  -u=20007128864 -p=200.178.251.146:8080 -c="JSESSIONID=0000Fi...." -d=111111 -t=cpf > debug.html

*/


if(count($argv) > 1) {
	foreach($argv as $arv){
		if(stristr($arv, '-p=')){
			$proxy = str_replace('-p=', '', $arv);
		}elseif(stristr($arv, '-u=')){
			$usuario = str_replace('-u=', '', $arv);
		}elseif(stristr($arv, '-c=')){
			$cookie = str_replace('-c=', '', $arv);
		}elseif(stristr($arv, '-d=')){
			$doc = str_replace('-d=', '', $arv);
		}elseif(stristr($arv, '-t=')){
			$tipo = str_replace('-t=', '', $arv);
		}
	}
}else{
	//die('falta parametros...');
}

if(!isset($tipo)) {
	die('informe o tipo');
}

if($usuario != null || $proxy != null || $cookie != null || $doc != null) {

	if($tipo == 'cpf') {

		$cpfcon = new cpfConsulta();
		$cpfcon->setCookie($cookie);
		$cpfcon->setProxy($proxy);
		$cpfcon->setCpf($doc);
		$run = $cpfcon->consultar();

	}elseif($tipo == 'cnpj') {

		$cnpjcon = new cnpjConsulta();
		$cnpjcon->setCookie($cookie);
		$cnpjcon->setProxy($proxy);
		$cnpjcon->setCnpj($doc);
		$run = $cnpjcon->consultar();

	}

	if($run === false) {
		$result = ['msg'=> 'erro ao consultar, cookie invalido', 'status' => false];
	}elseif($run === true) {
		$result = ['msg'=> 'erro ao consultar, cookie ok !', 'status' => true];
	}elseif(stristr($run, 'ES CONFIDENCIAIS')) {

		if($tipo == 'cpf') {
			$limpa = new Filtro();
			$res = $limpa->json($run);
		}else{
			$res = base64_encode($run);
		}

		$result = ['msg'=> 'ok', 'status' => true, 'doc'=>$doc, 'tipo'=>$tipo, 'dados' => $res];
	}elseif($run == 'rede'){
		$result = ['msg'=> 'rede ruim', 'status' => false];
	}
}else{
	$result = ['msg'=> 'nada a fazer ', 'status' => true];
}

echo json_encode($result);
