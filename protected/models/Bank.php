<?php
/**
 * Сущность: Банк.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $id
 * @property string $name
 * @property string $deleted
 */
class Bank extends SOAPModel
{
    const PREFIX_CACHE_BANK_NAME = '_name_';

	/**
	 * Объект модели
	 * @static
	 * @param string $className
	 * @return Bank
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

	/**
	 * Список банков
	 * @return Bank[]
	 */
	protected function findAll()
    {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listBanks($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Получить один банк
	 * @param string $id
	 * @return Bank[]
	 * @throws CHttpException
	 */
	public function findByPk($id)
    {
		$this->where('id', $id);
		return $this->findAll();
	}

    /**
     * @return array
     */
    public function attributeNames()
    {
        return array(
            'id',            // string
            'name',          // string
        );
    }

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels()
    {
		return array(
			'id'            => '#',
			'name'          => 'Название',
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
    {
		return array(
			array('name', 'required'),
		);
	}

    /**
     * Название банка по его идентификатору БИК или СВИФТ.
     * @param string $bank_id (BIK or SWIFT)
     * @param bool $force_cache
     * @return string
     */
    public function getName($bank_id, $force_cache=false)
    {
        $bank_name = '';
        if (!empty($bank_id)){
            $cache_id = __CLASS__.self::PREFIX_CACHE_BANK_NAME.$bank_id;
            if ($force_cache || ($bank_name = Yii::app()->cache->get($cache_id)) === false) {
                // BIK
                if (strlen($bank_id) == 9 && ctype_digit($bank_id)){
                    $banks = $this->where('deleted', false)->where('id', $bank_id)->findAll();
                } else {
                    $banks = $this->where('deleted', false)->where('swift', $bank_id)->findAll();
                }
                $bank_name = '';
                if (!empty($banks) && isset($banks[0]) && !empty($banks[0]->name)){
                    $bank_name = $banks[0]->name;
                    Yii::app()->cache->set($cache_id, $bank_name);
                }
            }
        }
        return $bank_name;
    }
}