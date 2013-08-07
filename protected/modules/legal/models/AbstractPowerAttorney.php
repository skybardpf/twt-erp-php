<?php
/**
 * Общие свойства и методы для реализации модели "Довереность".
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
abstract class AbstractPowerAttorney extends SOAPModel
{
    const PREFIX_CACHE_ID_FOR_MODEL_ID = '_model_id_';
//    const PREFIX_CACHE_ID_LIST_NAMES_BY_TYPE = '_list_names_by_type_';
    const PREFIX_CACHE_ID_LIST_MODELS_FOR_ORG_ID = '_list_models_for_org_id_';
    const PREFIX_CACHE_ID_LIST_NAMES_FOR_ORG_ID = '_list_names_for_org_id_';

    /**
     * Возвращает тип организации: Организация или контрагент.
     * @return string @see MTypeOrganization
     */
    abstract public function getTypeOrganization();

	/**
	 * Список доверенностей
	 * @return OrganizationPowerAttorney[] | ContractorPowerAttorney[]
	 */
	public function findAll()
    {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));
		$ret = $this->SOAP->listPowerAttorneyLE($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, get_class($this));
	}

	/**
	 * Доверенность
	 *
	 * @param $id
	 * @return bool|OrganizationPowerAttorney
	 * @internal param array $filter
	 */
	public function findByPk($id)
    {
		$ret = $this->SOAP->getPowerAttorneyLE(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

	/**
	 * Удаление Доверенности
	 * @return bool
	 */
	public function delete()
    {
		if ($this->primaryKey) {
			$ret = $this->SOAP->deletePowerAttorneyLE(array('id' => $this->primaryKey));
            $ret = SoapComponent::parseReturn($ret, false);
            if ($ret){
                $this->clearCache();
            }
            return $ret;
		}
		return false;
	}

    /**
     * Сбрасываем кэши.
     */
    public function clearCache()
    {
        $class = get_class($this);
//        $type = $this->getTypeOrganization();
        if ($this->primaryKey){
            Yii::app()->cache->delete($class . self::PREFIX_CACHE_ID_FOR_MODEL_ID.$this->primaryKey);
        }
//        Yii::app()->cache->delete($class . self::PREFIX_CACHE_ID_LIST_NAMES_BY_TYPE . $type);

        // TODO как очистить?
//        Yii::app()->cache->delete($class . self::PREFIX_CACHE_ID_LIST_NAMES_FOR_ORG_ID . $type);
//        Yii::app()->cache->delete($class . self::PREFIX_CACHE_ID_LIST_MODELS_FOR_ORG_ID);
    }

    /**
     * @param string $id
     * @param bool $force_cache
     * @return OrganizationPowerAttorney | ContractorPowerAttorney
     * @throws CHttpException
     */
    public function loadModel($id, $force_cache = false)
    {
        $cache_id = get_class($this) . self::PREFIX_CACHE_ID_FOR_MODEL_ID . $id;
        if ($force_cache || ($model = Yii::app()->cache->get($cache_id)) === false){
            $model = $this->findByPk($id);
            if ($model === null) {
                throw new CHttpException(404, 'Не найдена довереность.');
            }
            Yii::app()->cache->set($cache_id, $model);
        }
        return $model;
    }

    /**
     * @return OrganizationPowerAttorney | ContractorPowerAttorney
     */
    public function createModel()
    {
        return $this;
    }

    /**
     * Список доверенностей для указанной в $org_id организации.
     * @param string $org_id
     * @param bool $force_cache
     * @return OrganizationPowerAttorney | ContractorPowerAttorney
     */
    public function listModels($org_id, $force_cache = false)
    {
        $type = $this->getTypeOrganization();
        $cache_id = get_class($this) . self::PREFIX_CACHE_ID_LIST_MODELS_FOR_ORG_ID . '_' . $org_id;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false){
            $data = array();
            $models = $this
                ->where('deleted', false)
                ->where('type_yur', $type)
                ->where('id_yur', $org_id)
                ->findAll();
            if ($models){
                foreach($models as $model){
                    $data[] = $model;
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Список наименований довереностей для указанной в $org_id организации.
     * @param string $org_id
     * @param bool $force_cache
     * @return array Format [id => name]
     * @throws CException
     */
    public function listNames($org_id, $force_cache = false)
    {
        $type = $this->getTypeOrganization();
        $cache_id = get_class($this) . self::PREFIX_CACHE_ID_LIST_NAMES_FOR_ORG_ID.'_'.$org_id;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false){
            $data = array();
            $tmp = $this
                ->where('deleted', false)
                ->where('type_yur', $type)
                ->where('id_yur', $org_id)
                ->findAll();
            if ($tmp){
                foreach($tmp as $v){
                    $data[$v->primaryKey] = $v->name;
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }
}
