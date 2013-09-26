<?php
/**
 * Общая модель для работы с SOAP моделями.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $primaryKey
 * @property bool   $forceCached
 */
abstract class SOAPModel extends CModel
{
    /**
     * @var bool $forceCached Сбрасывать кэши принудительно. Используется для всех
     * функции, которые получают данные по SOAP.
     */
    private $_forceCached = false;

	/** Пока не реализована авторизация - для сохранения объектов надо передавать какого-то пользователя */
//	const USER_NAME = "test_user@user.test"; // TODO При реализации авторизации передавать правильное значение
    const PREFIX_CACHE_MODEL_PK = '_model_pk_';

    private static $_models = array();  // class name => model

	/**
	 * @var SoapComponent
	 */
	protected $SOAP = NULL;
	protected $_attributes = array();

	protected $where = array();
	protected $order = array();

    /**
     * EVERY derived SoapModel class must override this method as follows,
     * <pre>
     * public static function model($className=__CLASS__)
     * {
     *     return parent::model($className);
     * }
     * </pre>
     *
     * @param string $className active record class name.
     * @return SoapModel
     */
    public static function model($className=__CLASS__)
    {
        if(isset(self::$_models[$className]))
            return self::$_models[$className];
        else
        {
            $model=self::$_models[$className]=new $className(null);
            $model->attachBehaviors($model->behaviors());
            return $model;
        }
    }

    /**
     * @param bool $force
     */
    public function setForceCached($force = false)
    {
        $this->_forceCached = $force;
    }

    /**
     * @return bool
     */
    public function getForceCached()
    {
        return $this->_forceCached;
    }

    /**
     * Конструктор.
     */
    public function __construct()
	{
		$this->afterConstruct();
	}

    /**
     * Действия, которые можно произвести после создания объекта SOAPModel.
     */
    protected function afterConstruct()
	{
		$this->SOAP = Yii::app()->soap;
        if ($this->SOAP === null){
            throw new CHttpException(500, 'Не создан объект SOAP сервера');
        }
		parent::afterConstruct();
	}

	/**
	 * PHP setter magic method.
	 * This method is overridden so that AR attributes can be accessed like properties.
	 * @param string $name property name
	 * @param mixed $value property value
	 * @return mixed|void
	 */
	public function __set($name,$value)
	{
		if($this->setAttribute($name,$value)===false)
		{
			return parent::__set($name,$value);
		}
		return NULL;
	}

	/**
	 * Sets the named attribute value.
	 * You may also use $this->AttributeName to set the attribute value.
	 * @param string $name the attribute name
	 * @param mixed $value the attribute value.
	 * @return boolean whether the attribute exists and the assignment is conducted successfully
	 * @see hasAttribute
	 */
	public function setAttribute($name,$value)
	{
		if(property_exists($this,$name))
			$this->$name = $value;
		elseif(in_array($name, $this->attributeNames()))
			$this->_attributes[$name]=$value;
		else
			return false;
		return true;
	}

	/**
	 * PHP getter magic method.
	 * This method is overridden so that AR attributes can be accessed like properties.
	 * @param string $name property name
	 * @return mixed property value
	 * @see getAttribute
	 */
	public function __get($name)
	{
		if(isset($this->_attributes[$name])) {
			return $this->_attributes[$name];
		} elseif(in_array($name, $this->attributeNames()))
			return NULL;
		else
			return parent::__get($name);
	}

	/**
	 * Returns the named attribute value.
	 * If this is a new record and the attribute is not set before,
	 * the default column value will be returned.
	 * If this record is the result of a query and the attribute is not loaded,
	 * null will be returned.
	 * You may also use $this->AttributeName to obtain the attribute value.
	 * @param string $name the attribute name
	 * @return mixed the attribute value. Null if the attribute is not set or does not exist.
	 * @see hasAttribute
	 */
	public function getAttribute($name)
	{
		if(property_exists($this,$name))
			return $this->$name;
		elseif(isset($this->_attributes[$name]))
			return $this->_attributes[$name];
		return NULL;
	}

	/**
	 * Checks if a property value is null.
	 * This method overrides the parent implementation by checking
	 * if the named attribute is null or not.
	 * @param string $name the property name or the event name
	 * @return boolean whether the property value is null
	 */
	public function __isset($name)
	{
		if(isset($this->_attributes[$name]))
			return true;
		elseif(in_array($name, $this->attributeNames()))
			return false;
		else
			return parent::__isset($name);
	}

    /**
     * Геттер для получение первичного ключа. @see $primaryKey.
     * Может быть перкрыт в наследниках.
     * @return string
     */
    public function getPrimaryKey()
    {
		return $this->id;
	}

	/**
	 * Create object with data
	 * @param mixed $data
	 * @param SOAPModel $class
	 * @return SOAPModel | null
	 */
	public function publish_elem($data, $class)
    {
		if (!$data)
            return null;
		$obj = new $class;
		$obj->setAttributes($data, false);
		return $obj;
	}

	/**
	 * Create array of objects with data
	 * @param $data
	 * @param $class
	 * @return SOAPModel[]
	 */
	public function publish_list($data, $class)
    {
		$return = array();
		if (is_array($data)) {
			foreach ($data as $elem) {
				$return[] = $this->publish_elem($elem, $class);
			}
		}
		return $return;
	}

	/**
	 * Set ordering for finds
	 * @param $param
	 * @param string $dir
	 *
	 * @return SOAPModel
	 */
	public function order($param, $dir = 'asc') {
		foreach ($this->order as $key => $order) {
			if ($order['Поле'] == $param) unset($this->order[$key]); break;
		}
		$this->order[] = array('Поле' => $param, 'Значение' => $dir);
		return $this;
	}

	/**
	 * Set filtering for finds
	 * @param $param
	 * @param $value
	 *
	 * @return SOAPModel
	 */
	public function where($param, $value)
    {
		$this->where[$param] = $value;
		return $this;
	}

    /**
     * @return mixed
     */
    protected abstract function findAll();

    /**
     * @param string $attribute
     */
    public function validJson($attribute)
    {
        if (null === CJSON::decode($this->$attribute)){
            $this->addError($attribute, 'Не правильный формат JSON строки.');
        }
    }

    /**
     *  Валидатор. Проверяет, что имена файлов-сканов, которые хотят загрузить
     *  не совпадают с именами файлов, которые были загружены прежде.
     */
    public function existsScans()
    {
        if ($this->primaryKey) {
            $err = array();
            foreach ($this->upload_scans as $f) {
                if (in_array($f->name, $this->list_scans)){
                    $err[] = $f->name;
                }
            }
            if (!empty($err)){
                foreach($err as $e){
                    $this->addError('list_files', 'Скан с таким именем уже существует: '.$e);
                }
            }
        }
    }

    /**
     *  Валидатор. Проверяет, что имена файлов, которые хотят загрузить
     *  не совпадают с именами файлов, которые были загружены прежде.
     */
    public function existsFiles()
    {
        if ($this->primaryKey) {
            $err = array();
            foreach ($this->upload_files as $f) {
                if (in_array($f->name, $this->list_files)){
                    $err[] = $f->name;
                }
            }
            if (!empty($err)){
                foreach($err as $e){
                    $this->addError('list_files', 'Файл с таким именем уже существует: '.$e);
                }
            }
        }
    }
}