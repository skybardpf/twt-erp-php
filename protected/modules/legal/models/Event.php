<?php
/**
 * Модель: Мои события (мероприятия).
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @property string $id
 * @property string $name
 * @property array  $list_yur
 * @property array  $countries
 * @property bool   $for_yur       если "true" для юр.лица, если "false", то для страны.
 * @property bool   $made_by_user  если "true" создано юзером, иначе админом. Нелья удалять и редактировать.
 * @property bool   $deleted
 *
 * @property string $json_organizations  внутренняя переменая.
 * @property string $json_contractors    внутренняя переменая.
 * @property string $json_countries      внутренняя переменая.
 */
class Event extends SOAPModel {
    const FOR_ORGANIZATIONS = 1;
    const FOR_JURISDICTION = 2;

    public $upload_files;
    public $div_list_yur;
//    public $json_organizations;

	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return Event
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

	/**
	 * Список Мероприятий
	 *
	 * @return Event[]
	 */
	public function findAll()
    {
		$filters = SoapComponent::getStructureElement($this->where);
		$ret = $this->SOAP->listEvents(array(
            'filters' => (!$filters) ? array(array()) : $filters,
            'sort' => array($this->order)
        ));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Мероприятие
	 *
	 * @param $id
	 * @return bool|Event
	 * @internal param array $filter
	 */
	public function findByPk($id)
    {
		$ret = $this->SOAP->getEvent(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

    /**
     *  Сохранение события.
     *
     *  @return array
     *  @throws CHttpException
     */
    public function save()
    {
        $data = $this->getAttributes();
        $data['user'] = SOAPModel::USER_NAME;
        $data['for_yur'] = ($data['for_yur'] == self::FOR_ORGANIZATIONS) ? true : false;

        $countries = $data['countries'];
        $list_yur = $data['list_yur'];

        $id = ($this->primaryKey) ? $this->primaryKey : 0;
        if (!empty($this->upload_files)) {
            foreach ($this->upload_files as $f) {
                $uf = new UploadFile();
                $id = $uf->upload($f, UploadFile::CLIENT_ID, __CLASS__, $id, UploadFile::TYPE_FILE_FILES);
            }
        }
        $upload_ids = UploadFile::getListFiles(UploadFile::CLIENT_ID, __CLASS__, $id, UploadFile::TYPE_FILE_FILES);
        $files = array();
        foreach($upload_ids as $f){
            $files[] = $f['id'];
        }

        if (!$this->getprimaryKey()){
            unset($data['id']);
            $data['made_by_user']  = true;
        }
        unset($data['deleted']);
        unset($data['files']);
        unset($data['files_list']);
        unset($data['user']);
        unset($data['json_organizations']);
        unset($data['json_contractors']);
        unset($data['json_countries']);
        unset($data['list_yur']);
        unset($data['countries']);
//        unset($data['made_by_user']);

        $send = array(
            'data' => SoapComponent::getStructureElement($data),
        );
        if (!empty($files)){
            $send = array_merge($send, array('files_list' => $files));
        }
        if ($data['for_yur']){
            $send = array_merge($send, array('list_yur' => $list_yur));
        } else {
            $send = array_merge($send, array('countries_list' => $countries));
        }

        $ret = $this->SOAP->saveEvent($send);
        $ret = SoapComponent::parseReturn($ret, false);

        if (!$this->primaryKey) {
            if (!ctype_digit($ret)){
                foreach($upload_ids as $id){
                    $uf = new UploadFile();
                    $uf->delete_file($id);
                }
            } else {
                foreach($upload_ids as $id){
                    $uf = new UploadFile();
                    $uf->move($id, $ret);
                }
                throw new CHttpException(500, 'Ошибка при сохранении события');
            }
        } else {
            Yii::app()->cache->delete(__CLASS__.'_'.$this->primaryKey);
        }

        return $ret;
    }

    /**
     * Удаление Мероприятия
     *
     * @return bool
     */
    public function delete()
    {
        if ($pk = $this->getprimaryKey()) {
            $ret = $this->SOAP->deleteEvent(array('id' => $pk));
            return $ret->return;
        }
        return false;
    }

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels()
    {
		return array(
			'id'                => '#',                   // +
			'name'              => 'Название',            // +
			'list_yur'          => 'Юр. лица',
			'countries'         => 'Страны',
//			'type'              => 'Тип',
			'event_date'        => 'Первая дата наступления',
			'notification_date' => 'Первая дата напоминания',
            'period'            => 'Периодичность',
            'description'       => 'Описание',
            'files'             => 'Файлы',
			'deleted'           => 'На удаление',

            'made_by_user'      => '',
//			'user'              => 'Пользователь',
			'for_yur'           => 'Тип',
			'json_organizations' => '',
			'json_contractors'  => '',
			'json_countries'    => '',
		);
	}

    /**
     *  Валидация атрибутов.
     *
     *  @return array
     */
    public function rules()
    {
        return array(
//            array('countries', 'required'),
//            array('countries', 'in', 'range'  => array_keys(Countries::getValues())),
//
            array('json_organizations', 'validJson'),
            array('json_contractors', 'validJson'),
            array('json_countries', 'validJson'),

            array('list_yur', 'validListYur'),
            array('list_countries', 'validListCountries'),

//            array('typ_doc', 'in', 'range'  => array_keys(PowerAttorneysLE::getDocTypes())),

            array('name', 'required'),

            array('for_yur', 'required'),
            array('for_yur', 'in', 'range' => array(self::FOR_ORGANIZATIONS,self::FOR_JURISDICTION)),
            array('period', 'required'),

            array('event_date, notification_date', 'required'),
            array('event_date, notification_date', 'date', 'format' => 'yyyy-MM-dd'),

            array('description', 'safe'),
            array('made_by_user', 'boolean'),
        );
    }

    /**
     * @param string $attribute
     */
    public function validListYur($attribute)
    {
        if ($this->for_yur == self::FOR_ORGANIZATIONS){
            $org = CJSON::decode($this->json_organizations);
            $c  = CJSON::decode($this->json_contractors);
            if ((empty($org) && empty($c))){
                $this->addError($attribute, 'Укажите как минимум одно юр. лицо');
            }
        }
    }

    /**
     * @param string $attribute
     */
    public function validListCountries($attribute)
    {
//        var_dump($this->json_countries);die;
        if ($this->for_yur == self::FOR_JURISDICTION){

            $c  = CJSON::decode($this->json_countries);
            if (empty($c)){
                $this->addError($attribute, 'Укажите как минимум одну страну');
            }
        }
    }


    /**
     * @param string $attribute
     */
    public function validJson($attribute)
    {
        if ($this->for_yur == self::FOR_ORGANIZATIONS){
//            var_dump($this->json_countries);
            if (null === CJSON::decode($this->$attribute)){
                $this->addError($attribute, 'Не правильный формат строки JSON.');
            }
        }
    }

    /**
     * @return array
     */
    public function getStructureOrg()
    {
        $organizations = CJSON::decode($this->json_organizations);
        $contractors  = CJSON::decode($this->json_contractors);

        $ret = array();
        if (!empty($organizations)){
            foreach($organizations as $id){
                $ret[] = array(
                    'id_yur' => $id,
                    'type_yur' => 'Организации'
                );
            }
        }
        if (!empty($contractors)){
            foreach($contractors as $id){
                $ret[] = array(
                    'id_yur' => $id,
                    'type_yur' => 'Контрагенты'
                );
            }
        }
        return $ret;
    }

    /**
     * @static
     * @return array
     */
    public static function getPeriods()
    {
        return array(
            'ПоСобытию' => 'По событию',
            'Разовый' => 'Разовый',
            'День' => 'День',
            'Неделя' => 'Неделя',
            'Месяц' => 'Месяц',
            'Квартал' => 'Квартал',
            'Год' => 'Год',
        );
    }

    /**
     * @static
     * @return array
     */
    public static function getTypes()
    {
        return array(
            self::FOR_ORGANIZATIONS => 'Для юридического лица',
            self::FOR_JURISDICTION => 'Для юрисдикции'
        );
    }
}