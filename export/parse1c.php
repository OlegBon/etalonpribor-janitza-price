<?php


	/**
	 * Парсит цены и количество
	 *
	 * @param    string    наименование типа цены
	 */
	function parseOffers($filename, $config_price_type, $language_id) {
global $db;



$json = file_get_contents(ROOT_DIR."/wp-content/uploads/_data-pribor.js");
preg_match("#\[(.+?)\]#is", $json, $gh );
$data_types = json_decode($gh[0],true);


		$importFile = ROOT_DIR. '/export/' . $filename;
		$xml = simplexml_load_file($importFile);
		$price_types = array();
		//$data_types = array();
		$config_price_type_main = array();
		$enable_log = true;


		if ($enable_log)
			log_write("Начат разбор файла: " . var_export($data_types,true));

		
		$offer_cnt = 0;

		if ($xml->ПакетПредложений->Предложения->Предложение) {
			foreach ($xml->ПакетПредложений->Предложения->Предложение as $offer) {

				
				$offer_cnt++;
				
					
					$data = array();
					$data['price'] = 0;
					
					//UUID без номера после #
					$uuid = explode("#", $offer->Ид);
					$data['1c_id'] = $uuid[0];
					if ($enable_log)
						log_write("Товар: [UUID]:" . $data['1c_id']);
	

					
					if($offer->Наименование)$data['product_description'][$language_id]['name'] = (string)$offer->Наименование;
					$data['product_name']=(string)$offer->Модель;
					$data['1c_kod'] = trim( (string)$offer->Код );
					$data['disc'] = (string)$offer->ПроизводствоПрекращено;
					$sert = (string)$offer->Сертификат;
					$data['kol'] = (int)$offer->Количество;
					
						
	
					//Цена за единицу
					if ($offer->Цены) {
	
						// Первая цена по умолчанию - $config_price_type_main
						if (!$config_price_type_main['keyword']) {
							$data['price'] = (float)$offer->Цены->Цена->ЦенаЗаЕдиницу;
						}
						else {
							if ($offer->Цены->Цена->ИдТипаЦены) {
								foreach ($offer->Цены->Цена as $price) {
									if ($price_types[(string)$price->ИдТипаЦены] == $config_price_type_main['keyword']) {
										$data['price'] = (float)$price->ЦенаЗаЕдиницу;
										if ($enable_log)
											log_write(" найдена цена  > " . $data['price']);
	
									}
								}
							}
						}
	

					}
	
					//Количество
					$data['quantity'] = isset($offer->Количество) ? (int)$offer->Количество : 0;
					
					
Foreach($data_types as $key=>$ob){
	if($ob['kod1c'] == $data['1c_kod']){
		$ob['price'] = $data['price'];
		$ob['updated'] = date("Y-m-d");
		$data_types[$key]= $ob;
	}

}
			
				
					

				
			}
		}
ksort($data_types);
		if ($enable_log)
			log_write("Окончен разбор файла: " . var_export($data_types,true) );
		file_put_contents(ROOT_DIR."/wp-content/uploads/_data-pribor.js", "deviceData = ".json_encode($data_types, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK ).";\n\n console.log(\"deviceData (/wp-content/uploads/_data-pribor.js) loaded\"); ");
		//return $data_types;

	}
	
	
