<?php

define('ROOT_DIR',dirname(dirname(__FILE__)));
define('DIR_IMAGE', 'import_files/');


@include_once('parse1c.php');


$config['exchange1c_status'] = true;
$config['exchange1c_username'] = '{XXXXXXX}';
$config['exchange1c_password'] = 'XXXXXXX';
$config['exchange1c_allow_ip'] = 'XXXXXXX';


function log_write($log){
 $file = 'log.txt';
}

if (isset($_GET['mode']) && $_GET['type'] == 'catalog') {

	switch ($_GET['mode']) {
		case 'checkauth':
			modeCheckauth();
		break;

		case 'init':
			modeCatalogInit();
		break;

		case 'file':
			modeFile();
		break;

		case 'import':
			modeImport();
		break;

		default:
			echo "success\n";
	}

}

function modeCatalogInit($echo = true){

	log_write("загружается модель modeCatalogInit");

	
		$limit = 100000 * 1024;

		if ($echo) {
			echo "zip=no\n";
			echo "file_limit=".$limit."\n";
		}


}

function modeImport($manual = false) {
global $config,$_GET,$_COOKIE;

		$cache = ROOT_DIR.'/export/';

		if (isset($_GET['filename'])) {
			$filename = $_GET['filename'];
			$importFile = $cache . $filename;
		}
		else {
			echo "failure\n";
			echo "ERROR 10: No file name variable";
			return 0;
		}

		log_write("   загружается модель... ".date("F j, Y, G:i a"));


		if (strpos($filename, 'import') !== false) {



			log_write("загружается ".$filename);


				echo "success\n";


		} elseif (strpos($filename, 'offers') !== false) {




$data_of = parseOffers('offers.xml', 0, 0) ;





			log_write("загружается ".$filename);

				echo "success\n";

		}	else {
			echo "failure\n";
			echo $filename;
		}

		return;
	}


	function modeFile() {
global $config,$_GET,$_COOKIE;

 

		if (!isset($_COOKIE['key'])) {
			return;
		}

		if ($_COOKIE['key'] != md5($config['exchange1c_password'])) {
			echo "failure\n";
			echo "Session error";
			return;
		}

		$cache = ROOT_DIR.'/export/';

		// Проверяем на наличие имени файла
		if (isset($_GET['filename'])) {
			$uplod_file = $cache . $_GET['filename'];
		}
		else {
			echo "failure\n";
			echo "ERROR 10: No file name variable";
			return;
		}

		// Проверяем XML или изображения
		if (strpos($_GET['filename'], 'import_files') !== false) {
			$cache .= DIR_IMAGE;
			$uplod_file = $cache . basename($_GET['filename']);
			//return;
			//$this->checkUploadFileTree(dirname($_GET['filename']) , $cache);
			
		if(file_exists($uplod_file)) {
			echo "success\n";
			return;						  
									 }
			
			
		}
		
		

		// Получаем данные
		$data = file_get_contents("php://input");

		if ($data !== false) {
			if ($fp = fopen($uplod_file, "wb")) {
				$result = fwrite($fp, $data);

				if ($result === strlen($data)) {
					echo "success\n";

					chmod($uplod_file , 0777);
					//echo "success\n";
				}
				else {
					echo "failure\n";
				}
			}
			else {
				echo "failure\n";
				echo "Can not open file: $uplod_file\n";
				echo $cache;
			}
		}
		else {
			echo "failure\n";
			echo "No data file\n";
		}


	}




	function modeCheckauth() {
global $config;
		// Проверяем включен или нет модуль
		if (!$config['exchange1c_status']) {
			echo "failure\n";
			echo "1c module OFF";
			exit;
		}

		// Разрешен ли IP
		if ($config['exchange1c_allow_ip'] != '') {
			$ip = $_SERVER['REMOTE_ADDR'];
			$allow_ips = explode("\r\n", $config['exchange1c_allow_ip']);

			if (!in_array($ip, $allow_ips)) {
				echo "failure\n";
				echo "IP is not allowed";
				exit;
			}
		}


		// Авторизуем
		if (($config['exchange1c_username'] != '') && (@$_SERVER['PHP_AUTH_USER'] != $config['exchange1c_username'])) {
			echo "failure\n";
			echo "error login";
		}

		if (($config['exchange1c_password'] != '') && (@$_SERVER['PHP_AUTH_PW'] != $config['exchange1c_password'])) {
			echo "failure\n";
			echo "error password";
			exit;
		}

		echo "success\n";
		echo "key\n";
		echo md5($config['exchange1c_password']) . "\n";
	}