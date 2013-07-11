<?php

namespace Zeleznypa\Curl;

/**
 * cURL wrapper
 * @author Pavel Železný <info@pavelzelezny.cz>
 */
class Curl extends \Zeleznypa\Curl\SimpleCurl
{

	const
			DELETE = 'DELETE',
			GET = 'GET',
			POST = 'POST',
			PUT = 'PUT';

	/** @var array $arguments */
	private $arguments = array();

	/** @var string $communicationMethod */
	private $communicationMethod = self::GET;

	/** @var mixed $data */
	private $data;

	/** @var string $endpoint */
	private $endpoint;

	/** @var array $info */
	private $info;

	/** @var callable $serializeDataFunction */
	private $serializeDataFunction;

	/** @var string $url */
	private $url;

	/**
	 * Constructor
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @param string $url
	 * @return void
	 */
	public function __construct($url = NULL)
	{
		parent::__construct($url);
		$this->setSerializeDataFunction(array($this, 'serializeData'));
	}

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
	 * Communication method getter
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return string
	 */
	public function getCommunicationMethod()
	{
		return $this->communicationMethod;
	}

	/**
	 * Communication method setter
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @param string $communicationMethod
	 * @return \Zeleznypa\Curl\Curl Provides fluent interface
	 * @throws \UnexpectedValueException
	 */
	public function setCommunicationMethod($communicationMethod)
	{
		if (in_array($communicationMethod, $this->getAvailableCommunicationMethod()) === FALSE)
		{
			throw new \UnexpectedValueException('Requested communication method is not from allowed one ( ' . implode(', ', $this->getAvailableCommunicationMethod()) . ' )');
		}
		$this->communicationMethod = $communicationMethod;
		return $this;
	}

	/**
	 * Post data getter
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return mixed
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Post data setter
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @param mixed $data
	 * @return \Zeleznypa\Curl\Curl Provides fluent interface
	 */
	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	/**
	 * Endpoint getter
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return string
	 */
	public function getEndpoint()
	{
		return $this->endpoint;
	}

	/**
	 * Endpoint setter
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @param string $endpoint
	 * @return \Zeleznypa\Curl\Curl Provides fluent interface
	 */
	public function setEndpoint($endpoint)
	{
		$this->endpoint = '/' . ltrim($endpoint, '/');
		return $this;
	}

	/**
	 * cURL response info getter
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return array
	 */
	public function getInfo()
	{
		return $this->info;
	}

	/**
	 * Serialize data function setter
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return callable
	 */
	public function getSerializeDataFunction()
	{
		return $this->serializeDataFunction;
	}

	/**
	 * Serialize data function setter
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @param callable $serializeDataFunction
	 * @return \Zeleznypa\Curl\Curl Provides fluent interface
	 * @throws \UnexpectedValueException
	 */
	public function setSerializeDataFunction($serializeDataFunction)
	{
		if (is_callable($serializeDataFunction) === FALSE)
		{
			throw new \UnexpectedValueException('Serialize data function have to be callable');
		}
		$this->serializeDataFunction = $serializeDataFunction;
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
	 * Available communication method getter
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return array
	 */
	public function getAvailableCommunicationMethod()
	{
		return array(
			self::DELETE,
			self::GET,
			self::POST,
			self::PUT,
		);
	}

	/**
	 * Real request post fields data getter
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return string
	 */
	public function getRequestPostFields()
	{
		return call_user_func($this->getSerializeDataFunction(), $this->getData());
	}

	/**
	 * Real request url getter
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return string
	 */
	public function getRequestUrl()
	{
		$url = ($this->getEndpoint() !== NULL) ? rtrim($this->getUrl(), '/') . $this->getEndpoint() : $this->getUrl();
		$urlArguments = http_build_query($this->getArguments());
		if ($urlArguments === '')
		{
			return $url;
		}
		elseif (strpos($url, '?') === FALSE)
		{
			return $url . '?' . $urlArguments;
		}
		else
		{
			return $url . '&' . $urlArguments;
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
		$options[CURLOPT_CUSTOMREQUEST] = $this->getCommunicationMethod();
		$options[CURLOPT_POST] = $this->getCommunicationMethod() !== self::GET;
		$options[CURLOPT_RETURNTRANSFER] = TRUE;
		$options[CURLOPT_TIMEOUT] = 30;
		$options[CURLOPT_URL] = $this->getRequestUrl();

		/** Some clients are not happy, when they recieve POST data in GET request */
		if ($this->getCommunicationMethod() !== self::GET)
		{
			$options[CURLOPT_POSTFIELDS] = $this->getRequestPostFields();
		}
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

	/**
	 * Process cURL response
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return \Zeleznypa\Curl\Curl Provides fluent interface
	 */
	protected function processResponse()
	{
		parent::processResponse();
		$this->info = curl_getinfo($this->getHandler());
		return $this;
	}

	/**
	 * Serialize POST data
	 * @author Pavel Železný <info@pavelzelezny.cz>
	 * @return string
	 */
	protected function serializeData()
	{
		return http_build_query($this->getData());
	}

}