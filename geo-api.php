<?php
/*
	подключение:
		include $_SERVER['DOCUMENT_ROOT'] . '/lib/geo-api.php'; 
	использование:
		echo $city_ru;
		echo $country_iso;
		echo $country_ru;
	Определение гео-данных с помощью https://sypexgeo.net/ru/api/
	Лимит - 10 000 запросов в месяц с одного IP, данные записываются в куки
	На локалхосте работает некорректно, использовать на сервере
*/
if ( !isset($_COOKIE['city_ru']) || !isset($_COOKIE['country_iso']) || !isset($_COOKIE['country_ru']) ) {
		// значения по-умолчанию:
		$geo_cookie_days = 5; // период в сутках, на который записываются куки
		$city_ru = 'n/a';
		$country_iso = 'n/a';
		$country_ru = 'n/a';
		// Функция - регулярное выражение IPv4-адреса
		function is_ipv4($string)
		{return (bool) preg_match('/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/m', $string);}
		// Вычисляем IP
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && is_ipv4($_SERVER['HTTP_X_FORWARDED_FOR']))
				$geo_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				else $geo_ip = $_SERVER['REMOTE_ADDR'];
		// Получаем данные от API в JSON - формате
		$geo_api_request = 'https://api.sypexgeo.net/json/' . $geo_ip;
		$geo_info_json = file_get_contents ($geo_api_request);
		/* Пример результата обращения к API в JSON
		GET http://api.sypexgeo.net/json/123.45.67.89
		{"ip":"123.45.67.89",
		"city":{"id":1835848,"lat":37.566,"lon":126.9784,
		 "name_ru":"Сеул","name_en":"Seoul","okato":""},
		"region":{"id":1835847,"lat":37.58,"lon":127,"name_ru":"Сеул",
		 "name_en":"Seoul","iso":"KR-11","timezone":"Asia/Seoul","okato":""
		},
		"country":{"id":119,"iso":"KR","continent":"AS","lat":36.5,"lon":127.75,
		 "name_ru":"Южная Корея","name_en":"South Korea","timezone":"Asia/Seoul"
		}}
		*/

		if ($geo_info_json) { // проверка прошел ли запрос к api
			// Получаем ассоциативный php-массив с результатом запроса к API
			$geo_result = json_decode($geo_info_json, true);
			// полученные результаты:
			if ($geo_result['city']['name_ru'])
					$city_ru = $geo_result['city']['name_ru']; // Москва
			if ($geo_result['country']['iso'])
					$country_iso = $geo_result['country']['iso']; // RU
			if ($geo_result['country']['name_ru'])
					$country_ru = $geo_result['country']['name_ru']; // Россия
			// записываем полученные результаты в куки:
				setcookie ("city_ru", $city_ru, time()+60*60*24*$geo_cookie_days);
				setcookie ("country_iso", $country_iso, time()+60*60*24*$geo_cookie_days);
				setcookie ("country_ru", $country_ru, time()+60*60*24*$geo_cookie_days);
		}
	} else {
		// Если установлены куки, то считываем их и записываем в переменные
		$city_ru = $_COOKIE['city_ru'];
		$country_iso = $_COOKIE['country_iso'];
		$country_ru = $_COOKIE['country_ru'];
}
