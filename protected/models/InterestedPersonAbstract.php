<?php
/**
 * Общий класс для модели: "Заинтересованные персоны".
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string     $viewPerson
 *
 * @property string     $type_yur
 * @property string     $id_yur
 * @property string     $type_lico
 * @property string     $date_inauguration
 * @property string     $description
 * @property bool       $deleted
 * @property bool       $current_state
 */
abstract class InterestedPersonAbstract extends SOAPModel
{
    const PREFIX_CACHE_MODELS_BY_ORG = '_models_by_org_';
    const PREFIX_CACHE_REVISION_HISTORY_BY_ORG = '_revision_history_by_org_';

    /**
     * Возвращает тип заинтересованного лица.
     * @see MViewInterestedPerson
     * @return string
     */
    abstract public function getViewPerson();

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
        return $this->publish_list($ret, __CLASS__);
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
        $cache_id = __CLASS__ . self::PREFIX_CACHE_MODELS_BY_ORG . $orgId .'_' . $orgType . '_' . $date;
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
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Список доступных типов заинтересованных лиц.
     * @static
     * @return array
     */
    public static function getPersonTypes()
    {
        return array(
            MTypeInterestedPerson::ORGANIZATION => 'Организация',
            MTypeInterestedPerson::CONTRACTOR => 'Контрагент',
            MTypeInterestedPerson::INDIVIDUAL => 'Физическое лицо',
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
                    'date' => $this->date_inauguration,
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
            'type_yur',
            'id_yur',
            'type_lico',
            'date_inauguration',
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
//			'id'            => 'Лицо',
//			'role'          => 'Роль',
//			'add_info'      => 'Дополнительные сведения',
//			'cost'          => 'Номинальная стоимость пакета акций',
//			'percent'       => 'Величина пакета акций, %',
////			'vid'           => 'Вид лица', // (выбор из справочника юр. лиц или физ. лиц, обязательное); Физические лица
////			'cur'           => 'Валюта номинальной стоимости',
//			'deleted'       => 'Текущее состояние',
//			'id_yur'        => 'Юр.Лицо',
////			'name'          => 'Название',
//
//            'yur_url'       => '',
//            'type_yur'      => '',
//            'lico'          => 'Лицо',
//            'type_lico'     => 'Тип лица',
//            'nominal'       => 'Номинал акции',
//            'currency'      => 'Валюта',
//            'quantStock'    => 'Кол-во акций',
//            'date'          => 'Дата вступления в должность',
//            'dateIssue'     => 'Дата выпуска пакета акций',
//            'numPack'       => 'Номер пакета акций',
//            'typeStock'     => 'Вид акций',
//            'job_title'     => 'Наименование должности',
//
//            'list_individuals'     => 'Список физ. лиц',
//            'list_organizations'   => 'Список юр. лиц',
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

            array('date_inauguration', 'required'),
            array('date_inauguration', 'date', 'format' => 'yyyy-MM-dd'),

            array('description', 'length', 'max' => 200),
		);
	}
}