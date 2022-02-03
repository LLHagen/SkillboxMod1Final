<?php

	include $_SERVER['DOCUMENT_ROOT'] . '/include/functions.php';

	// получаем минимальную и максимальную цены для фильтра цен
	$rangePrice = getMinAndMaxPriceProducts();

	$result['min'] = $rangePrice[0]['min'];
	$result['max'] = $rangePrice[0]['max'];

	// получение гет пораметров (не работает через $_GET)
	$url = $_SERVER['HTTP_REFERER'];
	parse_str( parse_url( $url, PHP_URL_QUERY ), $getParam );

	$values1 = $getParam['minPrice'] ?? $result['min'];
	$values2 = $getParam['maxPrice'] ?? $result['max'];

	$result['values'] = [$values1, $values2];

	$result = json_encode($result);
	echo ($result);