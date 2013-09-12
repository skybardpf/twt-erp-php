<?php
/**
 * Корзина акционирования. Косвенная схема.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $id
 * @property string $id_object
 * @property string $type_subject
 * @property string $percent
 */
class IndirectShareholding extends SOAPModel
{
    const PREFIX_CACHE_LIST_MODELS_FOR_ORG = '_list_models_for_org_';
    const PREFIX_CACHE_LIST_INDIVIDUALS_FOR_ORG = '_list_individuals_for_org_';

    public $name_subject = '';
    public $url_subject = '';

    /**
     * @return void
     */
    public function afterConstruct()
    {
        $this->id = $this->id_subject.'_'.$this->type_subject;
        parent::afterConstruct();
    }

	/**
	 * @static
	 * @param string $className
	 * @return IndirectShareholding
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

    /**
     * @param string $orgId
     * @param string $orgType
     * @return void
     */
//    public function clearCache($orgId, $orgType)
//    {
//        $cache = Yii::app()->cache;
//        $cache->delete(__CLASS__.self::PREFIX_CACHE_LIST_MODELS_FOR_ORG.$orgId.'_'.$orgType);
//        $cache->delete(__CLASS__.self::PREFIX_CACHE_LIST_INDIVIDUALS_FOR_ORG.$orgId.'_'.$orgType);
//    }

	/**
	 * @return IndirectShareholding[]
	 */
	protected function findAll()
    {
		$ret = $this->SOAP->listInDirectShareHolding($this->where);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

    /**
     * @return array
     */
    public function attributeNames()
    {
        return array(
            'id',
            'id_subject',
            'type_subject',
            'percent',
        );
    }

    /**
     * Список моделей корзины акционирования. Косвенная схема.
     * @param string $individualId
     * @param string $orgId
     * @param string $orgType
     * @param bool $forceCached.
     * @return array
     */
    public function listModels($individualId, $orgId, $orgType, $forceCached = false)
    {
//        $cache_id = __CLASS__.self::PREFIX_CACHE_LIST_MODELS_FOR_ORG.$individualId.'_'.$orgId.'_'.$orgType;
//        if ($forceCached || ($data = Yii::app()->cache->get($cache_id)) === false) {
        $data = $this
            ->where('id', $individualId)
            ->where('id_yur', $orgId)
            ->where('type_yur', $orgType)
            ->findAll();

        $contractors = Contractor::model()->getListNames($this->getForceCached());
        $organizations = Organization::model()->getListNames($this->getForceCached());
        foreach ($data as $k => $v) {
            if ($v->type_subject == 'Организация') {
                $data[$k]['name_subject'] = (isset($organizations[$v->id_subject])) ? CHtml::encode($organizations[$v->id_subject]) : '';
                $data[$k]['url_subject'] = CHtml::link(
                    $data[$k]['name_subject'],
                    $data[$k]['url_subject'] = Yii::app()->createUrl('organization/view', array('id' => $v->id_subject))
                );
            } elseif ($v->type_subject == 'Контрагент') {
                $data[$k]['name_subject'] = (isset($contractors[$v->id_subject])) ? CHtml::encode($contractors[$v->id_subject]) : '';
                $data[$k]['url_subject'] = CHtml::link(
                    $data[$k]['name_subject'],
                    $data[$k]['url_subject'] = Yii::app()->createUrl('contractor/view', array('id' => $v->id_subject))
                );
            }
        }
//            Yii::app()->cache->set($cache_id, $data);
//        }
        return $data;
    }
}