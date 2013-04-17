<?php
/**
 * @method array __getFunctions() standard soap WSDL __getFunctions method
 */
class SoapComponent extends CApplicationComponent
{
	/** @var SoapClient */
	protected $soap_client = NULL;
	public $cache_enabled = 0;
	public $socket_timeout = 25;

	public $connection_options = array(); // Configurable one
	protected $_connection_options = array( // Default and really used one
		'soap_version'  => SOAP_1_2
	);

	// caching requests
	protected $requestCache             = false;
	protected $requestCachingDuration   = 0;
	protected $requestCachingDependency = NULL;

	public $wsdl = NULL;

	/**
	 * generate specialized "structure" array(array('Поле' => key, 'Значение' => value), ...)
	 * Вторым значением принимает массив опций, для конвертации:
	 * convert_boolean — если true, то преобразовывает булевые значения true и false в строки "true" и "false", по-умолчанию true
	 *
	 * @static
	 * @param array $properties
	 * @param array $options
	 * @return array
	 */
	static public function getStructureElement(array $properties = array(), $options = array()) {
		$ret = array();
		$options += array('convert_boolean' => true);
		foreach ($properties as $key => $val) {
			if ($options['convert_boolean'] && is_bool($val)) {
				$val = $val ? 'true' : 'false';
			}
			$ret[] = array('Поле' => $key, 'Значение' => $val);
		}
		return $ret;
	}

	/**
	 * @static
	 *
	 * @param $data
	 * @param bool $json
	 *
	 * @throws Exception
	 * @internal param $class - which objects we will generate
	 * @internal param $key - the first key of resulting array
	 *
	 * @return array
	 */
	static public function parseReturn($data, $json = true) {
		if (is_string($data->return) && strpos($data->return, 'error') !== false) {
			throw new Exception($data->return);
		} else {
			if (is_string($data->return) && $json) {
				$data = json_decode($data->return, true);
			} elseif (!$json) {
				$data = $data->return;
			} else {
				$data = NULL;
			}
		}
		return $data;
	}

	protected function delay_init() {
		/** Setting wsdl cache */
		if (ini_get('soap.wsdl_cache_enabled') != $this->cache_enabled) ini_set('soap.wsdl_cache_enabled', $this->cache_enabled);
		if (ini_get('default_socket_timeout') != $this->socket_timeout) ini_set('default_socket_timeout', $this->socket_timeout);

		$this->_connection_options = array_merge($this->_connection_options, $this->connection_options);
		$this->soap_client = new SoapClient($this->wsdl, $this->_connection_options);
		Yii::log('Connected to '.$this->wsdl, CLogger::LEVEL_INFO, 'soap');
	}

	public function cache($duration, $dependency = NULL) {
		$this->requestCachingDuration   = $duration;
		$this->requestCachingDependency = $dependency;
		$this->requestCache             = true;
		return $this;
	}

	public function __call($name, $parameters) {
		if ($this->requestCache) {
			$data = Yii::app()->cache->get('soap_'.md5($this->wsdl.'_'.$name.'_'.var_export($parameters, true)));
			if ($data === false) {
				$data = $this->soap_call($name, $parameters);
				Yii::app()->cache->set('soap_'.md5($this->wsdl.'_'.$name.'_'.var_export($parameters, true)), $data, $this->requestCachingDuration, $this->requestCachingDependency);
			}
			$this->requestCachingDuration   = 0;
			$this->requestCachingDependency = NULL;
			$this->requestCache             = false;

			return $data;
		} else {
			return $this->soap_call($name, $parameters);
		}
	}

	/**
	 * @param $name
	 * @param array $params
	 *
	 * @throws CHttpException
	 * @return mixed
	 */
	protected function soap_call($name, $params = array()) {
		if ($this->soap_client === NULL) {
			$this->delay_init();
		}
		if ($this->soap_method_exists($name)) {
			try {
				if (method_exists($this->soap_client, $name)) {
					$ret = call_user_func_array(array($this->soap_client, $name), $params);
				} else {
					if (YII_DEBUG) {
						//defined('JSON_UNESCAPED_UNICODE') or define('JSON_UNESCAPED_UNICODE', 0);
						Yii::log('try SOAP function ' . htmlspecialchars($name) . ' with args: ' . (defined('JSON_UNESCAPED_UNICODE') ? json_encode($params, JSON_UNESCAPED_UNICODE) : preg_replace('#\\\\u([0-9a-f]{4})#se','iconv("UTF-16BE","UTF-8",pack("H4","$1"))', json_encode($params))), CLogger::LEVEL_INFO, 'soap');
					}
					$ret = $this->soap_client->__soapCall($name, $params);
				}
				if (YII_DEBUG) Yii::log('function ' . $name . ' data: ' .(defined('JSON_UNESCAPED_UNICODE') ? json_encode($ret, JSON_UNESCAPED_UNICODE) : preg_replace('#\\\\u([0-9a-f]{4})#se','iconv("UTF-16BE","UTF-8",pack("H4","$1"))',json_encode($ret))), CLogger::LEVEL_INFO, 'soap');
				return $ret;
			} catch (Exception $e) {
				Yii::log($e->getCode().'(at file '.$e->getFile().':'.$e->getLine().'): '.$e->getMessage(),CLogger::LEVEL_ERROR, 'soap');
				throw new CHttpException(500, $e->getMessage());
			}
		} else {
			return parent::__call($name, $params);
		}
	}

	/**
	 * Checks the soap client methods and WSDL methods for existing
	 *
	 * @param $name
	 *
	 * @return bool
	 */
	protected function soap_method_exists($name) {
		if (method_exists($this->soap_client, $name)) return true;
		else {
			$methods = $this->soap_client->__getFunctions();
			if ($methods) {
				foreach ($methods as $m) {
					if (strpos($m, $name) !== false) return true;
				}
			}
		}
		return false;
	}

}