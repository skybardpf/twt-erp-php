<?php
/**
 * User: Forgon
 * Date: 09.01.13
 */
abstract class SOAPModel extends CModel {

	protected $SOAP = NULL;
	protected $_attributes = array();

	public static function model($className=__CLASS__) {
		return new $className();
	}

	public function __construct()
	{
		$this->afterConstruct();
	}

	protected function afterConstruct()
	{
		$this->SOAP = Yii::app()->soap;
		parent::afterConstruct();
	}

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeNames()
	{
		return array_keys($this->attributeLabels());
	}

	public function attributeLabels()
	{
		throw new Exception('Перечислите все поля');
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

	public function getprimaryKey() {
		return $this->id;
	}
}