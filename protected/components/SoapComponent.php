<?php
/**
 * Компонент SOAP для работы с ТВТ 1С
 *
 * @method array __getFunctions() standard soap WSDL __getFunctions method
 *
 * Организации (собственные Юр.Лица)
 * @method mixed listOrganizations      Список
 * @method mixed getOrganization        Просмотр
 * @method mixed saveOrganization       Сохранение
 * @method mixed deleteOrganization     Удаление
 *
 * Доверенности
 * @method mixed listPowerAttorneyLE    Список
 * @method mixed getPowerAttorneyLE     Просмотр
 * @method mixed savePowerAttorney      Сохранение
 * @method mixed deletePowerAttorneyLE  Удаление
 *
 * Физ.Лица
 * @method mixed listIndividuals        Список
 * @method mixed getIndividual          Просмотр
 * @method mixed saveIndividual         Сохранение
 * @method mixed deleteIndividual         Удаление
 *
 * Учредительные документы
 * @method mixed listFoundingDocuments  Список
 * @method mixed getFoundingDocument    Просмотр
 * @method mixed saveFoundingDocuments   Сохранение
 * @method mixed deleteFoundingDocument Удаление
 *
 * Свободные документы
 * @method mixed listFreeDocuments          Список
 * @method mixed getFreeDocument            Просмотр
 * @method mixed saveFreeDocument           Сохранение
 * @method mixed deleteFreeDocument         Удаление
 *
 * Банковские счета
 * @method mixed listSettlementAccounts     Список
 * @method mixed getSettlementAccount       Просмотр
 * @method mixed saveSettlementAccount      Сохранение
 * @method mixed deleteSettlementAccount    Удаление
 *
 * Мои события (мероприятия)
 * @method mixed listEvents                 Список
 * @method mixed getEvent                   Просмотр
 * @method mixed saveEvent                  Сохранение
 * @method mixed deleteEvent                Удаление
 *
 * Контрагенты
 * @method mixed listContragents            Список
 * @method mixed getContragent              Просмотр
 * @method mixed saveContragent             Сохранение
 * @method mixed deleteContragent           Удаление
 *
 * Заинтересованные лица
 * @method mixed listInterestedPersons      Список
 * @method mixed getInterestedPerson        Просмотр
 * @method mixed saveInterestedPerson      Сохранение
 * @method mixed deleteInterestedPersons    Удаление
 *
 * Заинтересованные лица - бенефициары
 * @method mixed listBeneficiary            Список
 * @method mixed getBeneficiary             Просмотр
 * @method mixed saveBeneficiary            Сохранение
 * @method mixed deleteBeneficiary          Удаление
 *
 * Страны {@see Countries}
 * @method mixed listCountries
 *
 * Контактные лица для контрагентов {@see ContactPersonForContractors}
 * @method mixed listContactPersonsForContractors(array $data)
 *
 * Контактные лица для организаций {@see ContactPersonForOrganization}
 * @method mixed listContactPersonsForOrganization(array $data)
 *
 * Коды ОКОПФ {@see CodesOKOPF}
 * @method mixed listOKOPF
 *
 * Договоры организации {@see Contract}
 * @method mixed listContracts(array $data)
 * @method mixed getContract(array $data)
 * @method mixed saveContract(array $data)
 * @method mixed deleteContract(array $data)
 *
 * Виды деятельности контрагентов (@see ContractorTypesActivities)
 * @method mixed listTypeActContr           Список
 *
 * Место расположения суда. (@see CourtLocation)
 * @method mixed listCourtLocations(array $data)
 *
 * Место заключения контрактов. (@see ContractPlace)
 * @method mixed listContractPlaces(array $data)
 *
 * Группы контрагентов. (@see ContractorGroup)
 * @method mixed listContractorGroups(array $data)
 * @method mixed getContractorGroup(array $data)
 * @method mixed saveContractorGroup(array $data)
 * @method mixed deleteContractorGroup(array $data)
 *
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
		if (isset($options['lang']) && $options['lang'] == 'eng'){
            $keyName = 'Field';
            $valName = 'Value';
        } else {
            $keyName = 'Поле';
            $valName = 'Значение';
        }

        $ret = array();
		$options += array('convert_boolean' => true);
		foreach ($properties as $key => $val) {
			if ($options['convert_boolean'] && is_bool($val)) {
				$val = $val ? 'true' : 'false';
			}
			$ret[] = array($keyName => $key, $valName => $val);
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
		if (is_string($data->return) && stripos($data->return, 'error') !== false) {
			throw new CException($data->return);
		} else {
			if (is_string($data->return) && $json) {
                $data = CJSON::decode($data->return);
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

		$time = microtime(true);
		Yii::log('Connecting to '.$this->wsdl, CLogger::LEVEL_INFO, 'soap');
		try {
			$this->soap_client = new SoapClient($this->wsdl, $this->_connection_options);
            if (empty($this->soap_client)){
                throw new CHttpException(500, 'Не удалось установить соединение с SOAP сервисом.');
            }
		} catch(Exception $e) {
			Yii::log($e->getCode().'(at file '.$e->getFile().':'.$e->getLine().'): '.$e->getMessage(),CLogger::LEVEL_ERROR, 'soap');
			throw new CHttpException(500, 'Не удалось установить соединение с SOAP сервисом.');
		}
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
			} else {
				Yii::log('soap from cache: function ' . htmlspecialchars($name) . ' with args: ' . (defined('JSON_UNESCAPED_UNICODE') ? json_encode($parameters, JSON_UNESCAPED_UNICODE) : preg_replace('#\\\\u([0-9a-f]{4})#se','iconv("UTF-16BE","UTF-8",pack("H4","$1"))', json_encode($parameters))), CLogger::LEVEL_INFO, 'soap');
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
	 * @param   string  $name
	 * @param   array   $params
	 *
	 * @throws  CHttpException
	 * @return  mixed
	 */
	protected function soap_call($name, $params = array())
    {
        if (YII_DEBUG) {
			$time = microtime(true);
			Yii::log('calling SOAP function ' . htmlspecialchars($name) . ' with args: ' . (defined('JSON_UNESCAPED_UNICODE') ? json_encode($params, JSON_UNESCAPED_UNICODE) : preg_replace('#\\\\u([0-9a-f]{4})#se','iconv("UTF-16BE","UTF-8",pack("H4","$1"))', json_encode($params))), CLogger::LEVEL_INFO, 'soap');
		}

		// init soap if needed
		if ($this->soap_client === NULL){
            $this->delay_init();
        }

		if ($this->soap_method_exists($name)) {
			try {
				if (method_exists($this->soap_client, $name)) {
					$ret = call_user_func_array(array($this->soap_client, $name), $params);
				} else {
					$ret = $this->soap_client->__soapCall($name, $params);
				}
				if (YII_DEBUG) {
					$time = microtime(true) - $time;
                    Yii::log(
						'function ' . $name . ' in '.$time.' seconds with data: ' .
							(defined('JSON_UNESCAPED_UNICODE')
								? json_encode($ret, JSON_UNESCAPED_UNICODE)
								: preg_replace('#\\\\u([0-9a-f]{4})#se','iconv("UTF-16BE","UTF-8",pack("H4","$1"))',json_encode($ret))
							),
						CLogger::LEVEL_INFO,
						'soap'
					);
				}

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
        // init soap if needed
        if (!is_resource($this->soap_client->sdl)){
            $this->delay_init();
        }

		if (method_exists($this->soap_client, $name)) {
            return true;
        }
        $methods = $this->soap_client->__getFunctions();
        if ($methods) {
            foreach ($methods as $m) {
                if (strpos($m, $name) !== false) return true;
            }
        }
		return false;
	}

    /**
     *  Возвращает формат структуры для дейстий в документах.
     *
     *  @static
     *  @param  SOAPModel $model
     *  @return array
     */
    public static function getStructureActions(SOAPModel $model)
    {
        $class = get_class($model);
        if (!in_array($class, array(' PowerAttorneyForOrganization', 'FoundingDocument'))){
            return false;
        }

        return array(
            "Name" => "СписокДействий",
            "Value" => array(
                'column' => array(),
                'index' => array(),
                'row'   => array(),
            )
        );
    }

    /**
     *  Возвращает формат структуры для сканов документов.
     *
     *  @static
     *  @param  SOAPModel $model
     *  @return array
     */
    public static function getStructureScans(SOAPModel $model)
    {
        $class = get_class($model);
        if (!in_array($class, array(' PowerAttorneyForOrganization', 'FoundingDocument'))){
            return false;
        }

        $cmd = Yii::app()->db->createCommand(
            'SELECT id, load_date
            FROM '.UploadFile::model()->tableName().'
            WHERE client_id = :client_id AND model_name=:model_name AND model_id=:model_id AND type=:type'
        );
        $files = $cmd->queryAll(true, array(
            ':client_id'    => UploadFile::CLIENT_ID,
            ':model_name'   => $class,
            ':model_id'     => $model->primaryKey,
            ':type'         => UploadFile::TYPE_FILE_SCANS,
        ));

        if (empty($files)) {
            $res = array(
                "Name" => "Сканы",
                "Value" => array(
                    'column' => array(),
                    'index' => array(),
                    'row' => array(),
                )
            );
        } else {
            $rows = array();
            foreach($files as $f){
                $rows[] = array(
                    'Value' => array(
                        $f['id'],
                        date('Y-m-d', $f['load_date'])
                    )
                );
            }
            $res = array(
                "Name" => "Сканы",
                "Value" => array(
                    'column' => array(
                        array("Name" => "Скан"),
                        array("Name" => "ДатаЗагрузки")
                    ),
                    'index' => array(),
                    'row'   => $rows,
                )
            );
        }
        return $res;
    }

    /**
     *  Возвращает правильный формат структуры для файлов, которая отдается на сохранение.
     *
     *  @static
     *  @param  SOAPModel $model
     *  @return array
     */
    public static function getStructureFiles(SOAPModel $model)
    {
        $class = get_class($model);
        if (!in_array($class, array(' PowerAttorneyForOrganization', 'FoundingDocument'))){
            return false;
        }

        $cmd = Yii::app()->db->createCommand(
            'SELECT id, load_date
            FROM '.UploadFile::model()->tableName().'
            WHERE client_id=:client_id AND model_name=:model_name AND model_id=:model_id AND type=:type'
        );
        $files = $cmd->queryAll(true, array(
            ':client_id'    => UploadFile::CLIENT_ID,
            ':model_name'   => $class,
            ':model_id'     => $model->primaryKey,
            ':type'         => UploadFile::TYPE_FILE_FILES,
        ));

        if (empty($files)) {
            $res = array(
                "Name" => "Файлы",
                "Value" => array(
                    'column' => array(),
                    'index' => array(),
                    'row' => array(),
                )
            );
        } else {
            $rows = array();
            foreach($files as $f){
                $rows[] = array(
                    'Value' => array(
                        $f['id'],
                        date('Y-m-d', $f['load_date'])
                    )
                );
            }
            $res = array(
                "Name" => "Файлы",
                "Value" => array(
                    'column' => array(
                        array("Name" => "Файл"),
                        array("Name" => "ДатаЗагрузки")
                    ),
                    'index' => array(),
                    'row'   => $rows,
                )
            );
        }
        return $res;
    }
}