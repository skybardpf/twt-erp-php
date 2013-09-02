<?php
/**
 * Общий класс для модели: "Заинтересованные персоны".
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string     $viewPerson
 * @property string     $pageTypePerson
 *
 * @property string     $person_name        // list
 * @property string     $date               // list
 *
 * @property string     $type_yur
 * @property string     $id_yur
 * @property string     $type_lico
 * @property string     $date_inaguration
 * @property string     $description
 * @property bool       $deleted
 * @property bool       $current_state
 */
abstract class InterestedPersonAbstract extends SOAPModel
{
    const PREFIX_CACHE_MODELS_BY_ORG = '_models_by_org_';
    const PREFIX_CACHE_REVISION_HISTORY_BY_ORG = '_revision_history_by_org_';
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
     * Список заинтересованных лиц
     * @param string $orgId
     * @param string $orgType
     * @param string $date
     * @param bool $forceCache
     * @return array
     */
    public function listModels($orgId, $orgType, $date, $forceCache=false)
    {
        $cache_id = __CLASS__ . self::PREFIX_CACHE_MODELS_BY_ORG.$orgId.'_'.$orgType.'_'.$date.'_'.$this->pageTypePerson;
        if ($forceCache || ($data = Yii::app()->cache->get($cache_id)) === false){
            $data = $this->where('id_yur', $orgId)
                ->where('type_yur', $orgType)
                ->where('date', $date)
                ->where('type_person', $this->viewPerson)
                ->findAll();
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * @return array
     */
    protected function findAllRevisionHistory()
    {
        $filters = SoapComponent::getStructureElement($this->where);
        $ret = $this->SOAP->listRevisionHistory(array(
            'filters' => ($filters == array()) ? array(array()) : $filters,
            'sort' => ($this->order == array()) ? array(array()) : $this->order
        ));
        $ret = SoapComponent::parseReturn($ret);
        return $ret;
    }

    /**
     * Список истории изменений
     * @param string $orgId
     * @param string $orgType
     * @param bool $forceCache
     * @return array
     */
    public function listRevisionHistory($orgId, $orgType, $forceCache=false)
    {
        $cache_id = __CLASS__ . self::PREFIX_CACHE_REVISION_HISTORY_BY_ORG . $orgId .'_' . $orgType;
        if ($forceCache || ($data = Yii::app()->cache->get($cache_id)) === false){
            $data = $this->where('id_yur', $orgId)
                ->where('type_yur', $orgType)
                ->where('type_person', $this->viewPerson)
                ->findAllRevisionHistory();

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
     * @param string $orgId
     * @param string $orgType
     * @param bool $forceCache
     * @return string
     */
    public function getLastDate($orgId, $orgType, $forceCache=false)
    {
        $cache_id = __CLASS__ . self::PREFIX_CACHE_LAST_HISTORY_DATE_BY_ORG . $orgId .'_' . $orgType;
        if ($forceCache || ($date = Yii::app()->cache->get($cache_id)) === false){
            $list_date = $this->listRevisionHistory($orgId, $orgType, $forceCache);
            $date = array_pop($list_date);
            if ($date === null){
                $date = new DateTime();
            }
            $date = $date->format('Y-m-d');
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
            1 => 'Действителен',
            0 => 'Недействителен',
        );
    }

	/**
	 * Удаление заинтересованного лица
	 * @return bool
	 */
	public function delete()
    {
		if ($this->primaryKey) {
			$ret = $this->SOAP->deleteInterestedPersons(
                array(
                    'id' => $this->primaryKey,
                    'type_lico' => $this->type_lico,
                    'id_yur' => $this->id_yur,
                    'type_yur' => $this->type_yur,
                    'date' => $this->date_inaguration,
                    'type_person' => $this->viewPerson
                )
            );
			return $ret->return;
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
            'date_inaguration',
            'description',
            'deleted',
            'current_state',
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
            'date_inaguration' => 'Дата вступления в должность',
            'current_state' => 'Текущее состояние',
            'description' => 'Дополнительные сведения',
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

			array('current_state', 'required'),

            array('date_inaguration', 'required'),
            array('date_inaguration', 'date', 'format' => 'yyyy-MM-dd'),

            array('description', 'length', 'max' => 200),
		);
	}
}