<?php
/**
 * Корзина акционирования. Прямая схема.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $id
 * @property string $id_object
 * @property string $type_object
 * @property string $id_subject
 * @property string $type_subject
 * @property string $percent
 */
class DirectShareholding extends SOAPModel
{
    const PREFIX_CACHE_LIST_MODELS_FOR_ORG = '_list_models_for_org_';
    const PREFIX_CACHE_LIST_INDIVIDUALS_FOR_ORG = '_list_individuals_for_org_';

    public $name_subject = '';
    public $name_object = '';
    public $url_subject = '';
    public $url_object = '';

    /**
     * @return void
     */
    public function afterConstruct()
    {
        $this->id = $this->id_object.'_'.$this->id_subject;
        parent::afterConstruct();
    }

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
     * @param string $orgId
     * @param string $orgType
     * @return void
     */
    public function clearCache($orgId, $orgType)
    {
        $cache = Yii::app()->cache;
        $cache->delete(__CLASS__.self::PREFIX_CACHE_LIST_MODELS_FOR_ORG.$orgId.'_'.$orgType);
        $cache->delete(__CLASS__.self::PREFIX_CACHE_LIST_INDIVIDUALS_FOR_ORG.$orgId.'_'.$orgType);
    }

	/**
	 * Список кодов.
	 * @return DirectShareholding[]
	 */
	protected function findAll()
    {
		$ret = $this->SOAP->listDirectShareHolding($this->where);
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
            'id_object',
            'type_object',
            'id_subject',
            'type_subject',
            'percent',
        );
    }

    /**
     * Список моделей корзины акционирования. Прямая схема.
     * @param string $orgId
     * @param string $orgType
     * @param bool $forceCached.
     * @return array
     */
    public function listModels($orgId, $orgType, $forceCached = false)
    {
        $cache_id = __CLASS__.self::PREFIX_CACHE_LIST_MODELS_FOR_ORG.$orgId.'_'.$orgType;
        if ($forceCached || ($data = Yii::app()->cache->get($cache_id)) === false) {
            $data = $this->where('id_yur', $orgId)
                ->where('type_yur', $orgType)
                ->findAll();

            $contractors = Contractor::model()->getListNames($this->getForceCached());
            $organizations = Organization::model()->getListNames($this->getForceCached());
            $individuals = Individual::model()->listNames($this->getForceCached());
            foreach ($data as $k => $v) {
                if ($v->type_subject == 'Организация') {
                    $data[$k]['name_subject'] = (isset($organizations[$v->id_subject])) ? CHtml::encode($organizations[$v->id_subject]) : '';
                    $data[$k]['url_subject'] = CHtml::link(
                        $data[$k]['name_subject'],
                        Yii::app()->createUrl('organization/view', array('id' => $v->id_subject))
                    );

                } elseif ($v->type_subject == 'Контрагент') {
                    $data[$k]['name_subject'] = (isset($contractors[$v->id_subject])) ? CHtml::encode($contractors[$v->id_subject]) : '';
                    $data[$k]['url_subject'] = CHtml::link(
                        $data[$k]['name_subject'],
                        $data[$k]['url_subject'] = Yii::app()->createUrl('contractor/view', array('id' => $v->id_subject))
                    );
                } elseif ($v->type_subject == 'Физические лица') {
                    $data[$k]['name_subject'] = (isset($individuals[$v->id_subject])) ? CHtml::encode($individuals[$v->id_subject]) : '';
                    $data[$k]['url_subject'] = CHtml::link(
                        $data[$k]['name_subject'],
                        $data[$k]['url_subject'] = Yii::app()->createUrl('individual/view', array('id' => $v->id_subject))
                    );
                }

                if ($v->type_object == 'Организация') {
                    $data[$k]['name_object'] = (isset($organizations[$v->id_object])) ? CHtml::encode($organizations[$v->id_object]) : '';
                    $data[$k]['url_object'] = CHtml::link(
                        $data[$k]['name_object'],
                        $data[$k]['url_object'] = Yii::app()->createUrl('organization/view', array('id' => $v->id_object))
                    );
                } elseif ($v->type_object == 'Контрагент') {
                    $data[$k]['name_object'] = (isset($contractors[$v->id_object])) ? CHtml::encode($contractors[$v->id_object]) : '';
                    $data[$k]['url_object'] = CHtml::link(
                        $data[$k]['name_object'],
                        $data[$k]['url_object'] = Yii::app()->createUrl('contractor/view', array('id' => $v->id_object))
                    );
                } elseif ($v->type_object == 'Физические лица') {
                    $data[$k]['name_object'] = (isset($individuals[$v->id_object])) ? CHtml::encode($individuals[$v->id_object]) : '';
                    $data[$k]['url_object'] = CHtml::link(
                        $data[$k]['name_object'],
                        $data[$k]['url_object'] = Yii::app()->createUrl('individual/view', array('id' => $v->id_object))
                    );
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Список моделей корзины акционирования. Прямая схема.
     * @param string $orgId
     * @param string $orgType
     * @param bool $forceCached.
     * @return array
     */
    public function getIndividuals($orgId, $orgType, $forceCached = false)
    {
        $cache_id = __CLASS__.self::PREFIX_CACHE_LIST_INDIVIDUALS_FOR_ORG.$orgId.'_'.$orgType;
        if ($forceCached || ($data = Yii::app()->cache->get($cache_id)) === false) {
            $models = $this->listModels($orgId, $orgType, $forceCached);
            $data = array();
            $individuals = Individual::model()->listNames($forceCached);
            foreach($models as $model){
                if ($model->type_object == 'Физические лица')
                    $data[$model->id_object] = (isset($individuals[$model->id_object])) ? $individuals[$model->id_object] : $model->id_object;
                if ($model->type_subject == 'Физические лица')
                    $data[$model->id_subject] = (isset($individuals[$model->id_object])) ? $individuals[$model->id_subject] : $model->id_subject;
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }
}