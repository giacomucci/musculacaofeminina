<?php

$client = new SoapClient('https://www.musculacaofeminina.com.br/api/soap/?wsdl');

// If somestuff requires api authentification,
// then get a session token
$session = $client->login('flavio', 'flavio');

$result = $client->call($session, 'catalog_product.info', '04096');

print_r($result);

?>