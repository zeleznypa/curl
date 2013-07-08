<?php

namespace Zeleznypa\Curl;

/**
 * cURL wrapper
 * @author Pavel Železný <info@pavelzelezny.cz>
 */
class Curl extends \Zeleznypa\Curl\SimpleCurl
{

	/**
	 * Real request cURL options getter
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return array
	 */
	public function getRequestOptions()
	{
		return $this->getDefaultOptions() + $this->getOptions();
	}

	/**
	 * Get default cURL options
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return array
	 */
	protected function getDefaultOptions()
	{
		$options[CURLOPT_CONNECTTIMEOUT] = 30;
		$options[CURLOPT_RETURNTRANSFER] = TRUE;
		$options[CURLOPT_TIMEOUT] = 30;
		return $options;
	}

	/**
	 * Process cURL options
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return \Zeleznypa\Curl\Curl Provides fluent interface
	 */
	protected function processOptions()
	{
		curl_setopt_array($this->getHandler(), $this->getRequestOptions());
		return $this;
	}

}