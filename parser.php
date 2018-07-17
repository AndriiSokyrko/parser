<?php
	function get_fcontent( $url, $javascript_loop = 0, $timeout = 5 ) {
		$url = str_replace( "&amp;", "&", urldecode( trim( $url ) ) );
		ob_start();
		$cookie = tempnam( "/tmp", "CURLCOOKIE" );
		$ch     = curl_init();
		curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
		curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		$content  = curl_exec( $ch );
		$response = curl_getinfo( $ch );
		curl_close( $ch );
		$head = ob_get_contents();
		ob_end_clean();
		if ( $response['http_code'] == 301 || $response['http_code'] == 302 ) {
			ini_set( "user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );

			if ( $headers = get_headers( $response['url'] ) ) {
				foreach ( $headers as $value ) {
					if ( substr( strtolower( $value ), 0, 9 ) == "location:" ) {
						return get_url( trim( substr( $value, 9, strlen( $value ) ) ) );
					}
				}
			}
		}


		return $content;
	}

	$remote_url = "https://imennaya-igrushka.ru/shop/kupit-imennuyu-igrushku";

	//create dir
	$dir = substr_replace($remote_url,'',0,7);
	$fileDir= __DIR__. $dir;
//	echo $fileDir;
  if(!is_dir($fileDir))
			mkdir($fileDir ,0755, true);
   $file='';

	//Cicle for pagination
$i=1;
while(check_url($remote_url.'?page='.$i)){
	$url= $remote_url.'?page='.$i;
	$content = get_fcontent( $remote_url . $file );

	preg_match_all( '/<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12".*>.*(?=<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12".*>)/Uism', $content, $result );/* for url */
//	print_r($result[0]);
//	exit;

	foreach ( $result[0] as $item ) {


		preg_match_all( '/<div class="card-content">.*<\/div>/Uism', $item, $result_content );/* for url */
		file_put_contents($fileDir."/text.html", $result_content[0][0], FILE_APPEND  );

		preg_match_all( '/<div class="card-action">.*<\/div>/Uism', $item, $result_action );/* for url */
		file_put_contents($fileDir."/text.html", $result_action[0][0], FILE_APPEND  );

		preg_match_all( '/<div class="card-image">.*<\/div>/Uism', $item, $result_img );/* for url */
		file_put_contents($fileDir."/text.html", $result_img[0][0] , FILE_APPEND  );
//		print_r($result_img[0][0]);
//		exit;
		file_put_contents($fileDir."/text.html", '-----------------------------------------------\n\r', FILE_APPEND  );
		preg_match_all( '/<img src="(.*)\">/Uism', $result_img[0][0], $url_img );/* for url */
print_r( htmlspecialchars($result_img[0][0]));
print_r( $url_img);
		exit;
		t($url_img[0], $remote_url) ;
	}
exit;
	$i++;
}


	//for cach page

//	if ( file_exists( $_SERVER['SERVER_NAME'] . '/common_.css' ) && filesize( $_SERVER['SERVER_NAME'] . '/common_.css' ) > 0 ) {
//		$content = get_fcontent( $_SERVER['SERVER_NAME'] . '/common_.css' );
//	} else {
//		$content = get_fcontent( $remote_url . $file );
//		file_put_contents( $_SERVER['DOCUMENT_ROOT'] . '\common_.css', $content );
//	}

//		preg_match_all( '/href="(\/page.*)\">/Uism', $content, $href_file );/* for url */
//
//	if(count($href_file[1])>0) {
//		for ( $i = 0; $i < count( $href_file[1] ); $i ++ ) {
//			print_r( $remote_url . $href_file[1][ $i ] . '<br>' );
//			$content = get_fcontent( $remote_url . $href_file[1][ $i ] );
//			preg_match_all( '/url\((.*)\)/Uism', $content, $result );/* for url */
//			if(count($result[1])>0) {
//				t( $result, $remote_url );
//			}
//		}
//	} else {
//		preg_match_all( '/url\((.*)\)/Uism', $content, $result );/* for url */
//		t($result, $remote_url) ;
//
//	}


	// check page if exist
function check_url($url){
	if ($otvet=@get_headers($url)){
//		return substr($otvet[0], 9, 3);
		return true;
	}
	return false;
}
	//$result - arrays url image
	//$remote_url - url site
	function t($result, $remote_url) {
		print_r($result);
		//preg_match_all( '/background: url\((.*)\)/Uism', $content, $result );/* for url */
		$len = count( $result[1] );
		if ( $len == 0 ) {
			die( 'path  not found' );
		}
		echo $len;

		for ( $x = 0; $x < $len; $x ++ ) {

			$parts    = explode( '/', $result[1][ $x ] );
			$name_img = array_pop( $parts );

echo  $remote_url .'/'. $result . '/' . $name_img;
			exit;
			$current = get_fcontent( $remote_url .'/'. $result . '/' . $name_img );
			file_put_contents( __DIR__ .'/'. $result . '/' . $name_img, $current );

		}
	}

?>