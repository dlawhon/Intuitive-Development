<?php
//positionstack free coordinate http API
$key = "34033a473cbc333c8188c2ae96a7dffd";

$queryString = http_build_query([
  'access_key' => $key = "34033a473cbc333c8188c2ae96a7dffd",
  //'query' => '197 West 1st Avenue Elkins Arkansas',
  'query' => '600 W Markham St Little Rock Arkansas 72201',
  'region' => 'Akansas',
  'output' => 'json',
  'limit' => 1
]);

$ch = curl_init(sprintf('%s?%s', 'http://api.positionstack.com/v1/forward', $queryString));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($ch);
curl_close($ch);

$apiResult = json_decode($json, true);

echo "<pre>";
print_r($apiResult);
echo "</pre>";

$data = array('latitude' => $apiResult['data'][0]['latitude'], 'longitude' => $apiResult['data'][0]['longitude']);

return($data);
