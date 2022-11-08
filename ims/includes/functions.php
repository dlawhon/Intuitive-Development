<?php
/**
 * Font Awesome CDN Setup Webfont
 *
 * This will load Font Awesome from the Font Awesome Free or Pro CDN.
 */
if (! function_exists('fa_custom_setup_cdn_webfont') ) {
  function fa_custom_setup_cdn_webfont($cdn_url = '', $integrity = null) {
    $matches = [];
    $match_result = preg_match('|/([^/]+?)\.css$|', $cdn_url, $matches);
    $resource_handle_uniqueness = ($match_result === 1) ? $matches[1] : md5($cdn_url);
    $resource_handle = "font-awesome-cdn-webfont-$resource_handle_uniqueness";

    foreach ( [ 'wp_enqueue_scripts', 'admin_enqueue_scripts', 'login_enqueue_scripts' ] as $action ) {
      add_action(
        $action,
        function () use ( $cdn_url, $resource_handle ) {
          wp_enqueue_style( $resource_handle, $cdn_url, [], null );
        }
      );
    }

    if($integrity) {
      add_filter(
        'style_loader_tag',
        function( $html, $handle ) use ( $resource_handle, $integrity ) {
          if ( in_array( $handle, [ $resource_handle ], true ) ) {
            return preg_replace(
              '/\/>$/',
              'integrity="' . $integrity .
              '" crossorigin="anonymous" />',
              $html,
              1
            );
          } else {
            return $html;
          }
        },
        10,
        2
      );
    }
  }
}

function hashPassword($password)
{
	$salt = bin2hex(openssl_random_pseudo_bytes(255));
	$hash = crypt($password, '$2a$11$' . $salt);

	$result['salt'] = $salt;
	$result['hash'] = $hash;

	return ($result);
}

function setPassword($id, $password)
{
  global $conn;

	$password = hashPassword($password);
	$salt = $password['salt'];
	$hash = $password['hash'];

  $userPrepare = $conn->prepare("UPDATE users SET salt = :salt, hash = :hash WHERE user_id = :user_id");
  $userPrepare->execute(array(
    ":salt" => $salt,
    ":hash" => $hash,
    ":user_id" => $id));
}

function getCoordinates($address, $city, $state, $zip) {

  //positionstack free coordinate http API
  //$key = "34033a473cbc333c8188c2ae96a7dffd";
  $key = "01ec3304008c6a896c9a701a0531d06b";

  $queryString = http_build_query([
    'access_key' => $key,
    'query' => $address . " " . $city . " " . $state . " " . $zip,
    'region' => $state,
    'output' => 'json',
    'limit' => 1,
  ]);

  $ch = curl_init(sprintf('%s?%s', 'http://api.positionstack.com/v1/forward', $queryString));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $json = curl_exec($ch);
  curl_close($ch);

  $apiResult = json_decode($json, true);
  $data = array('latitude' => $apiResult['data'][0]['latitude'], 'longitude' => $apiResult['data'][0]['longitude']);

  return($data);
}
