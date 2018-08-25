<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	if ( ! function_exists( 'exchange' ) ) {
		
		/**
		 * undocumented function
		 *
		 * @return void
		 * @author 
		 **/
		function exchange( $amount = 0 )
		{

			// $usdtobtc 		= 1 / $this->block_io->get_current_value('USD');

			//if ($from == 'USD'){
				 
				$price = number_format( blockchain_to_btc( $amount )  ,8,'.',''); 
			
			//}

			return $price;

		}

	}


	/**
	 * sak currency pirang BTC
	 *
	 * @return void
	 * @author 
	 **/
	function blockchain_to_btc( $amount = 0, $currency = 'USD' )
	{

		$curl 	= getUrlContent('https://blockchain.info/tobtc?currency='.$currency.'&value='.$amount.'');
		return $curl;
	}


	/**
	 * 1 BTC pirang currency
	 * daftar currency tersedia lihat di : https://blockchain.info/ticker
	 * @return void
	 * @author 
	 **/
	function blockchain_exchange( $get_currency = 'USD' )
	{
		$curl 		= getUrlContent('https://blockchain.info/ticker');
		$result 	= json_decode( $curl );

		if ( isset( $result->$get_currency ) ) {
			$output 	= $result->$get_currency->sell;
		}
		else {
			$output 	= false;
		}

		return $output;
	}


	/*==================================
	=            BTC FORMAT            =
	==================================*/
	/**
	 * echo formatBTC(convertToBTCFromSatoshi(5000));
	 *
	 * @return void
	 * @author 
	 **/
	function convertToBTCFromSatoshi($value) {
	    $BTC = $value / 100000000 ;
	    return $BTC;
	}

	function convertToSatoshi($value) {
	    $BTC = $value * 100000000 ;
	    return $BTC;
	}

	function formatBTC($value, $currency = true) {
	    $value = sprintf('%.8f', $value);

	    if ( $currency == true ) {
	    	$currency 	= ' BTC';
	    }else {
	    	$currency 	= '';
	    }

	    $value = convertToBTCFromSatoshi(rtrim($value, '0'))  .  $currency ;
	    return $value;
	}

	function formatCOIN($value) {
	    $value = sprintf('%.8f', $value);
	    $value = convertToBTCFromSatoshi(trim($value, '0')) . '';
	    return $value;
	}


	function getUrlContent($url){
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$data = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return ($httpcode>=200 && $httpcode<300) ? $data : false;
	
	}
