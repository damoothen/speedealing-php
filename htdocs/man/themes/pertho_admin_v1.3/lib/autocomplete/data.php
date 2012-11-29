<?php

if (version_compare(phpversion(), "5.3.0", ">=")  == 1)
  error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
else
  error_reporting(E_ALL & ~E_NOTICE); 

	

switch ($_GET['mode']) {
    case 'xml': // using XML file as source of data
		
		$sParam = $_GET['q'];
		if (!$sParam) exit;

		$doc = new DOMDocument();
		$doc->load( 'data.xml' );
		
		$items = $doc->getElementsByTagName( "item" );
		if (count($items) <= 0) exit;
		foreach( $items as $item ) {
			$value = $item->nodeValue;
			if (strpos(strtolower($value), strtolower($sParam)) !== false) {
				echo "$value\n";
			}
		}

        break;
    case 'sql': // using database as source of data
		require_once('classes/CMySQL.php');
		
		$sParam = $GLOBALS['MySQL']->escape($_GET['q']); // escaping external data
		if (!$sParam) exit;
		
        $sRequest = "SELECT `country_name` FROM `s85_countries` WHERE `country_name` LIKE '%{$sParam}%' ORDER BY `country_code`";
        $aItemInfo = $GLOBALS['MySQL']->getAll($sRequest);
        foreach ($aItemInfo as $aValues) {
            echo $aValues['country_name'] . "\n";
        }
        break;
}