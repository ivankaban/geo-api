# geo-api
	подключение:
		include $_SERVER['DOCUMENT_ROOT'] . '/your_folder/geo-api.php'; 
	использование:
		echo $city_ru;
		echo $country_iso;
		echo $country_ru;
	Определение гео-данных с помощью https://sypexgeo.net/ru/api/
	Лимит - 10 000 запросов в месяц с одного IP, данные записываются в куки
	На локалхосте работает некорректно, использовать на сервере
