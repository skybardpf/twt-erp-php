<?php
/**
 * Корзина акционирования. Прямая схема.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $id_object
 * @property string $type_object
 * @property string $id_subject
 * @property string $type_subject
 * @property string $percent
 */
class DirectShareholding extends SOAPModel
{
    const PREFIX_CACHE_LIST_MODELS_FOR_ORG = '_list_models_for_org_';

	/**
	 * @static
	 * @param string $className
	 * @return DirectShareholding
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

	/**
	 * Список кодов.
	 * @return DirectShareholding[]
	 */
	protected function findAll()
    {
        $filters = SoapComponent::getStructureElement($this->where);
        if (!$filters) $filters = array(array());
        $request = array('filters' => $filters, 'sort' => array(array()));
		$ret = $this->SOAP->listDirectShareHolding($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

    /**
     * @return array
     */
    public function attributeNames()
    {
        return array(
            'id_object',
            'type_object',
            'id_subject',
            'type_subject',
            'percent',
        );
    }

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels()
    {
		return array(
//			'id' => '#',
//			'name' => 'Название',
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
//	public function rules()
//    {
//		return array(
////			array('name', 'required'),
//		);
//	}

    /**
     * Список моделей корзины акционирования. Прямая схема.
     * @param string $orgId
     * @param string $orgType
     * @param bool $forceCached.
     * @return array
     */
    public function listModels($orgId, $orgType, $forceCached = false)
    {
        $cache_id = __CLASS__.self::PREFIX_CACHE_LIST_MODELS_FOR_ORG.$orgId;
        if ($forceCached || ($data = Yii::app()->cache->get($cache_id)) === false) {
            $data = $this->where('id_yur', $orgId)
                ->where('type_yur', $orgType)
                ->findAll();
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }
}