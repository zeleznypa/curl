<?php

namespace Zeleznypa\Curl;

/**
 * Simple cURL wrapper
 * @author Pavel Železný <info@pavelzelezny.cz>
 */
class SimpleCurl
{

	/** @var resource $handler cURL handle on success, false on errors. */
	private $handler;

	/** @var array $options */
	private $options = array();

	/** @var string|bool $result Depend on option CURLOPT_RETURNTRANSFER */
	private $result;

	/**
	 * Constructor
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @param string $url
	 * @return void
	 */
	public function __construct($url = NULL)
	{
		if ($url !== NULL)
		{
			$this->setUrl($url);
		}
	}

	/**
	 * Execute cURL request with defined params
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return \Zeleznypa\Curl\SimpleCurl Provides fluent interface
	 */
	public function execute()
	{
		curl_setopt_array($this->getHandler(), $this->getOptions());
		$this->result = curl_exec($this->getHandler());
		curl_close($this->getHandler());
		return $this;
	}

	/**
	 * Get cURL options
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * Get cURL option
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @param int $key One from cURL options
	 * @param mixed $default Value returned if option is not present
	 * @return mixed 
	 */
	public function getOption($key, $default = FALSE)
	{
		return (isset($this->options[$key]) === TRUE) ? $this->options[$key] : $default;
	}

	/**
	 * Set cURL options by array
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @param array $options
	 * @return \Zeleznypa\Curl\SimpleCurl Provides fluent interface
	 */
	public function setOptions(array $options)
	{
		foreach ($options as $key => $value)
		{
			$this->setOption($key, $value);
		}
		return $this;
	}

	/**
	 * Set cURL option
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @param int $key
	 * @param mixture $value
	 * @return \Zeleznypa\Curl\SimpleCurl Provides fluent interface
	 */
	public function setOption($key, $value)
	{
		$this->options[$key] = $value;
		return $this;
	}

	/**
	 * Get cURL responsponse result
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return string|bool Depend on option CURLOPT_RETURNTRANSFER
	 */
	public function getResult()
	{
		return $this->result;
	}

	/**
	 * Get cURL destination address
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return string
	 */
	public function getUrl()
	{
		return $this->getOption(CURLOPT_URL, NULL);
	}

	/**
	 * Simplier way to set url
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @param string $url
	 * @return \Zeleznypa\Curl\SimpleCurl Provides fluent interface
	 */
	public function setUrl($url)
	{
		return $this->setOption(CURLOPT_URL, $url);
	}

	/**
	 * CURL handler getter
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return resource cURL on success, false on error
	 */
	protected function getHandler()
	{
		if ($this->handler === NULL)
		{
			$this->handler = curl_init();
		}

		return $this->handler;
	}

}