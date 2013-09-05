<?php
/**
 * Общий класс для модели: "Заинтересованные персоны".
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string     $viewPerson
 * @property string     $pageTypePerson
 *
 * @property string     $person_name
 * @property string     $date
 * @property string     $type_yur
 * @property string     $id_yur
 * @property string     $type_lico
 * @property string     $description
 * @property bool       $deleted
 * @property int        $number_stake
 */
abstract class InterestedPersonAbstract extends SOAPModel
{
    const PREFIX_CACHE_MODELS_BY_ORG = '_models_by_org_';
    const PREFIX_CACHE_ALL_DATA_BY_ORG = '_all_data_by_org_';
    const PREFIX_CACHE_LIST_HISTORY_BY_ORG = '_list_history_by_org_';
    const PREFIX_CACHE_LAST_HISTORY_DATE_BY_ORG = '_last_history_date_by_org_';

    /**
     * Возвращает тип заинтересованного лица.
     * @see MViewInterestedPerson
     * @return string
     */
    abstract public function getViewPerson();

    /**
     * Возвращает тип заинтересованного лица для страницы.
     * @see MPageTypeInterestedPerson
     * @return string
     */
    abstract public function getPageTypePerson();

    /**
     * @return array
     */
    abstract public function listPersonTypes();

    public function afterConstruct()
    {
        $this->type_lico = MTypeInterestedPerson::INDIVIDUAL;
        parent::afterConstruct();
    }

	/**
	 * Список заинтересованных лиц
	 * @return array
	 */
	protected function findAll()
    {
        $filters = SoapComponent::getStructureElement($this->where);
        $ret = $this->SOAP->listInterestedPersons(array(
            'filters' => ($filters == array()) ? array(array()) : $filters,
            'sort' => ($this->order == array()) ? array(array()) : $this->order
        ));
        $ret = SoapComponent::parseReturn($ret);
        return $this->publish_list($ret, get_class($this));
	}

    /**
     * Получение заинтересованного лица.
     * @param string $id
     * @param string $typeLico
     * @param string $orgId
     * @param string $orgType
     * @param string $date
     * @param string $number_stake
     * @param bool   $forceCached
     * @return InterestedPersonAbstract
     * @throws CHttpException
     */
    public function findByPk($id, $typeLico, $orgId, $orgType, $date, $number_stake, $forceCached=false)
    {
        $class = get_class($this);
        $cache_id = $class.self::PREFIX_CACHE_MODEL_PK.$id.'_'.$typeLico.'_'.$orgId.'_'.$orgType.'_'.$date.'_'.$number_stake;
        if ($forceCached || ($model = Yii::app()->cache->get($cache_id)) === false){
            $model = $this->SOAP->getInterestedPerson(
                array(
                    'id' => $id,
                    'type_lico' => $typeLico,
                    'id_yur' => $orgId,
                    'type_yur' => $orgType,
                    'date' => $date,
                    'number_stake' => $number_stake,
                    'type_person' => $this->viewPerson,
                )
            );
            $model = SoapComponent::parseReturn($model);
            $model = $this->publish_elem(current($model), $class);
            if ($model === null)
                throw new CHttpException(404, 'Не найдено заинтересованное лицо');

            Yii::app()->cache->set($cache_id, $model);
        }
        $model->forceCached = $forceCached;
        return $model;
    }

    /**
     * Список заинтересованных лиц
     * @param string $orgId
     * @param string $orgType
     * @param string $date
     * @param bool $forceCached
     * @return array
     */
    public function listModels($orgId, $orgType, $date, $forceCached=false)
    {
        $cache_id = get_class($this).self::PREFIX_CACHE_MODELS_BY_ORG.$orgId.'_'.$orgType.'_'.$date;
        if ($forceCached || ($data = Yii::app()->cache->get($cache_id)) === false){
            $models = $this->listAllData($orgId, $orgType, $forceCached);
            $data = (isset($models[$date])) ? $models[$date] : array();

            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Список истории изменений по датам.
     * @param string $orgId
     * @param string $orgType
     * @param bool $forceCached
     * @return array
     */
    public function listHistory($orgId, $orgType, $forceCached=false)
    {
        $cache_id = get_class($this).self::PREFIX_CACHE_LIST_HISTORY_BY_ORG.$orgId.'_'.$orgType;
        if ($forceCached || ($data = Yii::app()->cache->get($cache_id)) === false){
            $models = $this->listAllData($orgId, $orgType, $forceCached);
            $data = array_keys($models);

            usort($data, function($a, $b){
                $ad = new DateTime($a);
                $bd = new DateTime($b);
                if ($ad == $bd) {
                    return 0;
                }
                return ($ad < $bd) ? -1 : 1;
            });
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Список истории изменений
     * @param string $orgId
     * @param string $orgType
     * @param bool $forceCached
     * @return array
     */
    protected function listAllData($orgId, $orgType, $forceCached=false)
    {
        $class = get_class($this);
        $cache_id = $class.self::PREFIX_CACHE_ALL_DATA_BY_ORG.$orgId.'_'.$orgType;
        if ($forceCached || ($data = Yii::app()->cache->get($cache_id)) === false){
            $filters = SoapComponent::getStructureElement(array(
                'id_yur' => $orgId,
                'type_yur' => $orgType,
                'type_person' => $this->viewPerson,
            ));
            $models = $this->SOAP->listInterestedPersonRevisionHistory(array(
                'filters' => $filters,
                'sort' => array(array())
            ));
            $models = SoapComponent::parseReturn($models);
            $models = $this->publish_list($models, $class);

            $data = array();
            foreach($models as $model){
                $model->id_yur = $orgId;
                $model->type_yur = $orgType;
                $model->person_name = CHtml::link(
                    CHtml::encode($model->person_name),
                    Yii::app()->createUrl(
                        'interested_person_'.$this->pageTypePerson.'/view',
                        array(
                            'id' => $model->id,
                            'type_lico' => $model->type_lico,
                            'id_yur' => $model->id_yur,
                            'type_yur' => $model->type_yur,
                            'date' => $model->date,
                            'number_stake' => $model->number_stake,
                        )
                    )
                );
                $data[$model->date][] = $model;
            }

            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * @param string $orgId
     * @param string $orgType
     * @param bool $forceCached
     * @return string
     */
    public function getLastDate($orgId, $orgType, $forceCached=false)
    {
        $cache_id = get_class($this).self::PREFIX_CACHE_LAST_HISTORY_DATE_BY_ORG . $orgId .'_' . $orgType;
        if ($forceCached || ($date = Yii::app()->cache->get($cache_id)) === false){
            $list_date = $this->listHistory($orgId, $orgType, $forceCached);
            $date = array_pop($list_date);
            if ($date === null){
                $date = new DateTime();
                $date = $date->format('Y-m-d');
            }
            Yii::app()->cache->set($cache_id, $date);
        }
        return $date;
    }

    /**
     * Список доступных типов заинтересованных лиц.
     * @return array
     */
    public function getPersonTypes()
    {
        return array(
            MTypeInterestedPerson::ORGANIZATION => 'Организация',
            MTypeInterestedPerson::CONTRACTOR => 'Контрагент',
            MTypeInterestedPerson::INDIVIDUAL => 'Физическое лицо',
        );
    }

    /**
     * Список состояний заинтересованного лица.
     * @return array
     */
    public function getStatuses()
    {
        return array(
            0 => 'Действителен',
            1 => 'Недействителен',
        );
    }

    /**
     * Чистим кеш.
     * @param InterestedPersonAbstract $model
     */
    public function clearCache(InterestedPersonAbstract $model)
    {
        $class = get_class($model);
        $cache = Yii::app()->cache;
        $cache->delete($class.self::PREFIX_CACHE_MODEL_PK.$model->primaryKey.'_'.$model->type_lico.'_'.$model->id_yur.'_'.$model->type_yur.'_'.$model->date.'_'.$model->number_stake);
        $cache->delete($class.self::PREFIX_CACHE_MODELS_BY_ORG.$model->id_yur.'_'.$model->type_yur.'_'.$model->date);
        $cache->delete($class.self::PREFIX_CACHE_ALL_DATA_BY_ORG.$model->id_yur.'_'.$model->type_yur);
        $cache->delete($class.self::PREFIX_CACHE_LAST_HISTORY_DATE_BY_ORG.$model->id_yur.'_'.$model->type_yur);
        $cache->delete($class.self::PREFIX_CACHE_LIST_HISTORY_BY_ORG.$model->id_yur.'_'.$model->type_yur);
    }

    /**
     * Сохранение заинтересованного лица.
     * @param array $data
     * @param InterestedPersonAbstract $old_model
     * @return array Если успешно, сохранилось, возвращает массив со значениями:
     * [id, type_lico, id_yur, type_yur, date],
     * иначе возвращает NULL.
     */
    public function saveData(array $data, InterestedPersonAbstract $old_model = null)
    {
        $ret = $this->SOAP->saveInterestedPerson(array(
            'data' => SoapComponent::getStructureElement($data),
        ));
        $tmp = SoapComponent::parseReturn($ret, true);
        if ($tmp === null)
            return $tmp;

        $this->clearCache($old_model);
        $ret = array();
        foreach($tmp as $v){
            $ret[key($v)] = current($v);
        }
        return $ret;
    }

	/**
	 * Удаление заинтересованного лица
	 * @return bool
	 */
	public function delete()
    {
		if ($this->primaryKey) {
			$ret = $this->SOAP->deleteInterestedPerson(
                array(
                    'id' => $this->primaryKey,
                    'type_lico' => $this->type_lico,
                    'id_yur' => $this->id_yur,
                    'type_yur' => $this->type_yur,
                    'date' => $this->date,
                    'number_stake' => $this->number_stake,
                    'type_person' => $this->viewPerson
                )
            );
            $ret = SoapComponent::parseReturn($ret, false);
            if ($ret){
                $this->clearCache($this);
            }
            return $ret;
		}
		return false;
	}

    /**
     * @return array
     */
    public function attributeNames()
    {
        return array(
            'id',
            'person_name',
            'date',
            'type_yur',
            'id_yur',
            'type_lico',
            'description',
            'deleted',
            'number_stake',
        );
    }

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels()
    {
		return array(
            'type_lico' => 'Тип',
            'date' => 'Дата вступления в должность',
            'deleted' => 'Текущее состояние',
            'description' => 'Дополнительные сведения',
            'number_stake' => 'Номер пакета акций',
        );
	}

    /**
     * Общие правила.
     * @return array
     */
    public function rules()
	{
		return array(
			array('type_lico', 'required'),
            array('type_lico', 'in', 'range' => array_keys(self::getPersonTypes())),

			array('deleted', 'required'),
			array('deleted', 'in', 'range' => array(0,1)),

            array('date', 'required'),
            array('date', 'date', 'format' => 'yyyy-MM-dd'),

            array('description', 'length', 'max' => 200),

            array('number_stake', 'numerical', 'integerOnly' => true, 'min'=> 0),
		);
	}
}