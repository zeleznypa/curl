<?php

namespace Zeleznypa\Curl;

/**
 * cURL wrapper
 * @author Pavel Železný <info@pavelzelezny.cz>
 */
class Curl extends \Zeleznypa\Curl\SimpleCurl
{

	/** @var array $arguments */
	private $arguments = array();

	/** @var string $url */
	private $url;

	/**
	 * URL argument getter
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @param string|integer $argumentName
	 * @param mixed $default
	 * @return mixed URL argument value or $default if argument is not set.
	 * @throws \BadMethodCallException
	 */
	public function getArgument($argumentName, $default = NULL)
	{
		if (array_key_exists($argumentName, $this->arguments))
		{
			return $this->arguments[$argumentName];
		}
		else
		{
			if (func_num_args() < 2)
			{
				throw new \BadMethodCallException('Missing argument "' . $argumentName . '".');
			}
			return $default;
		}
	}

	/**
	 * Url argument setter
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @param string|integer $argumentName
	 * @param string|integer $argumentValue
	 * @param boolean $overwrite Allow overwrite already defined argument
	 * @return \Zeleznypa\Curl\Curl Provides fluent interface
	 * @throws \BadMethodCallException
	 */
	public function setArgument($argumentName, $argumentValue, $overwrite = FALSE)
	{
		if ((array_key_exists($argumentName, $this->arguments) === FALSE) || ($overwrite === FALSE))
		{
			$this->arguments[$argumentName] = $argumentValue;
			return $this;
		}
		else
		{
			throw new \BadMethodCallException('Argument "' . $argumentName . '" has already been set.');
		}
	}

	/**
	 * URL arguments getter
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return array
	 */
	public function getArguments()
	{
		return $this->arguments;
	}

	/**
	 * URL arguments setter
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @param array $arguments
	 * @param boolean $overwrite Allow overwrite already defined argument
	 * @return \Zeleznypa\Curl\Curl Provides fluent interface
	 */
	public function setArguments(array $arguments, $overwrite = FALSE)
	{
		foreach ($arguments as $argumentName => $argumentValue)
		{
			$this->setArgument($argumentName, $argumentValue, $overwrite);
		}
		return $this;
	}

	/**
	 * Get cURL destination address
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * Simplier way to set url
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @param string $url
	 * @return \Zeleznypa\Curl\Curl Provides fluent interface
	 */
	public function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}

	/**
	 * Real request url getter
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return string
	 */
	public function getRequestUrl()
	{
		$urlArguments = http_build_query($this->getArguments());
		if ($urlArguments === '')
		{
			return $this->getUrl();
		}
		elseif (strpos($this->getUrl(), '?') === FALSE)
		{
			return $this->getUrl() . '?' . $urlArguments;
		}
		else
		{
			return $this->getUrl() . '&' . $urlArguments;
		}
	}

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
		$options[CURLOPT_URL] = $this->getRequestUrl();
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