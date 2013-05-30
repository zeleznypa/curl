<?php

namespace Zeleznypa\Curl;

/**
 * Simple cURL wrapper
 * @author Pavel Železný <info@pavelzelezny.cz>
 */
class SimpleCurl
{

	/**
	 * Execute cURL request with defined params
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @param string $url
	 * @param array $options
	 * @return string|bool Depend on option CURLOPT_RETURNTRANSFER
	 */
	public function execute($url, array $options = array())
	{
		$ch = curl_init($url);
		curl_setopt_array($ch, $options);
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}

}