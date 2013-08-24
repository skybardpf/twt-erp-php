<?php
/**
 * Модель: Мои события (мероприятия).
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $id
 * @property string $name
 * @property array  $list_yur
 * @property array  $countries
 * @property array  $list_files
 * @property string $event_date
 * @property bool   $for_yur       если "true" для юр.лица, если "false", то для страны.
 * @property bool   $made_by_user  если "true" создано юзером, иначе админом. Нелья удалять и редактировать.
 * @property bool   $deleted
 *
 * @property UploadDocument $uploadDocument
 * @method  bool upload(string $path, CUploadedFile $file)
 * @method  void removeFiles(string $path, array $files)
 * @method  void moveFiles(string $source, string $destination, array $files)
 */
class Event extends SOAPModel {
    const FOR_ORGANIZATIONS = 1;
    const FOR_JURISDICTION = 2;

    const PREFIX_CACHE_MODEL_ID = '_model_id_';
    const PREFIX_CACHE_LIST_MODELS = '_list_models';
    const PREFIX_CACHE_LIST_MODELS_BY_ORG = '_list_models_by_org_id_';
    const PREFIX_CACHE_LIST_MODELS_ALL_ORGANIZATION = '_list_models_all_organization';
    const PREFIX_CACHE_LIST_MODELS_ALL_COUNTRIES = '_list_models_all_countries';
    const PREFIX_CACHE_LIST_MODELS_ALL_ORG = '_list_models_all_org';
    const PREFIX_CACHE_LIST_MODELS_BY_COUNTRY = '_list_models_by_country_id_';

    // Для внутренних нужд
    public $div_list_yur = '';
    public $upload_files = array();
    public $json_exists_files;
    public $json_organizations;
    public $json_contractors;
    public $json_countries;

	/**
	 * @static
	 * @param string $className
	 * @return Event
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

    /**
     *
     */
    protected function afterConstruct()
    {
        $this->attachBehaviors($this->behaviors());
        parent::afterConstruct();
    }

    /**
     * Подключаем поведение для загрузки файлов.
     * @return array
     */
    public function behaviors()
    {
        return array(
            'uploadDocument' => array(
                'class' => 'application.components.Behavior.UploadDocument',
                'uploadDir' => Yii::getPathOfAlias(Yii::app()->params->uploadDocumentDir),
            ),
        );
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
     * @return Event
     * @throws CHttpException
     */
    public function createModel()
    {
//        $this->id_yur    = $org->primaryKey;
//        $this->type_yur  = "Организации";
//        $this->from_user = true;
//        $this->user      = SOAPModel::USER_NAME;
        $this->list_files = array();
//        $this->list_scans = array();
        return $this;
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
        if (!$this->primaryKey){
            unset($data['id']);
            $data['made_by_user']  = true;
        }

        $data['user'] = SOAPModel::USER_NAME;
        $data['for_yur'] = ($data['for_yur'] == self::FOR_ORGANIZATIONS) ? true : false;

        if ($data['for_yur']){
            $countries = array();
            $list_yur = $this->getStructureOrg($data['list_yur']);
        } else {
            $countries = $data['countries'];
            $list_yur = array();
        }

        $list_files = array();
        $id = ($this->primaryKey) ? $this->primaryKey : 'tmp_id';
        $path = Yii::app()->user->getId(). DIRECTORY_SEPARATOR . __CLASS__ . DIRECTORY_SEPARATOR . $id;
        $path_files = $path . DIRECTORY_SEPARATOR . MDocumentCategory::FILE;
        foreach ($this->upload_files as $f) {
            if ($this->upload($path_files, $f)){
                $list_files[] = $f->name;
            }
        }
        $list_files = array_merge($list_files, $this->list_files);
        $list_files = (empty($list_files)) ? array('Null') : $list_files;

        unset($data['deleted']);
        unset($data['list_files']);
        unset($data['upload_scans']);
        unset($data['user']);
        unset($data['json_exists_files']);
        unset($data['json_organizations']);
        unset($data['json_contractors']);
        unset($data['json_countries']);
        unset($data['list_yur']);
        unset($data['countries']);

        $send = array(
            'data' => SoapComponent::getStructureElement($data),
            'list_files' => $list_files,
        );
        if ($data['for_yur']){
            $send['list_yur'] = $list_yur;
        } else {
            $send['list_countries'] = $countries;
        }

        $ret = $this->SOAP->saveEvent($send);
        $ret = SoapComponent::parseReturn($ret, false);

        /**
         * Если создается новая довереность:
         * 1. Возникли ошибки - удаляем все документы из временной диретории.
         * 2. Все нормально - переносим документы из временной папки в папку
         * созданного документа ($this->primaryKey).
         */
        if (!$this->primaryKey) {
            try {
                if (!ctype_digit($ret)){
                    $this->removeFiles($path_files, $list_files);
                } else {
                    $path = Yii::app()->user->getId()
                        .DIRECTORY_SEPARATOR . __CLASS__
                        .DIRECTORY_SEPARATOR . $ret;
                    $dest_files = $path.DIRECTORY_SEPARATOR.MDocumentCategory::FILE;
                    $this->moveFiles($path_files, $dest_files, $list_files);
                }
            } catch (UploadDocumentException $e){
                Yii::log($e->getMessage(), cLogger::LEVEL_ERROR);
                $this->addError('id', $e->getMessage());
            }
        }
        $this->clearCache();
        return $ret;
    }

    /**
     * Удаление Мероприятия
     *
     * @return bool
     */
    public function delete()
    {
        if ($pk = $this->primaryKey) {
            $ret = $this->SOAP->deleteEvent(array('id' => $pk));
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
        if ($this->primaryKey){
            Yii::app()->cache->delete($class . self::PREFIX_CACHE_MODEL_ID . $this->primaryKey);
        }
//        if ($this->id_yur){
//            Yii::app()->cache->delete($class . self::PREFIX_CACHE_ID_LIST_DATA . $this->id_yur);
//        }
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
//			'id_yur'            => '',
			'list_yur'          => 'Юр. лица',
			'countries'         => 'Страны',
			'event_date'        => 'Первая дата наступления',
			'notification_date' => 'Первая дата напоминания',
            'period'            => 'Периодичность',
            'description'       => 'Описание',
            'list_files'        => 'Файлы',
			'deleted'           => 'На удаление',

            'made_by_user'      => '',
			'for_yur'           => 'Тип',
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
            array('json_exists_files', 'validJson'),

            array('list_yur', 'validListYur'),
            array('list_countries', 'validListCountries'),

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

    /**
     * @param string $id Идентификатор события.
     * @param bool $force_cache
     * @return Event
     * @throws CHttpException
     */
    public function loadModel($id, $force_cache=false)
    {
        $cache_id = __CLASS__.self::PREFIX_CACHE_MODEL_ID.$id;
        if ($force_cache || ($model = Yii::app()->cache->get($cache_id)) === false){
            $model = $this->findByPk($id);
            if ($model === null) {
                throw new CHttpException(404, 'Не найдено событие.');
            }
            $model->list_yur = $this->_parseListYur($model->list_yur);
            Yii::app()->cache->set($cache_id, $model);
        }
        return $model;
    }

    /**
     * Получаем список событий.
     * @param bool $force_cache
     * @return Event[]
     */
    protected function listModels($force_cache=false)
    {
        $cache_id = __CLASS__.self::PREFIX_CACHE_LIST_MODELS;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false){
            $data = Event::model()
                ->where('deleted', false)
                ->findAll();
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Получаем список событий для организации.
     * @param string $org_id
     * @param string $filter
     * @param bool $force_cache
     * @return Event[]
     */
    public function listModelsByOrg($org_id, $filter, $force_cache=false)
    {
        $cache_id = __CLASS__.self::PREFIX_CACHE_LIST_MODELS_BY_ORG.$org_id.'_'.$filter;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false){
            $org = $this->listModelsByAllOrg($force_cache);
            $org = $org['Организации'];
            $tmp = (isset($org[$org_id])) ? $org[$org_id] : array();

            $data = array();
            if ($filter == 'year'){
                $date = new DateTime();
                $year = new DateTime();
                $year->add(new DateInterval('P1Y'));
                foreach($tmp as $v){
                    $d = new DateTime($v->event_date);
                    if ($d >= $date && $d <= $year){
                        $data[] = $v;
                    }
                }
            } elseif ($filter == 'ten'){
                usort($tmp, array("Event", "_sortEventDate"));
                $date = new DateTime();
                $count = 0;
                foreach($tmp as $v){
                    $d = new DateTime($v->event_date);
                    if ($d >= $date){
                        $data[] = $v;
                        $count++;
                        if ($count == 9){
                            break;
                        }
                    }
                }
            } else {
                $data = $tmp;
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Получаем список событий по странам.
     * @param string $country_id
     * @param bool $force_cache
     * @return Event[]
     */
    public function listModelsByCountry($country_id, $force_cache=false)
    {
        $cache_id = __CLASS__.self::PREFIX_CACHE_LIST_MODELS_BY_COUNTRY.$country_id;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false){
            $countries = $this->listModelsByAllCountries($force_cache);
            $data = (isset($countries[$country_id])) ? $countries[$country_id] : array();
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Получаем список событий по всем странам.
     * @param bool $force_cache
     * @return Event[]
     */
    public function listModelsByAllCountries($force_cache=false)
    {
        $cache_id = __CLASS__.self::PREFIX_CACHE_LIST_MODELS_ALL_COUNTRIES;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false){
            $models = $this->listModels($force_cache);
            $data = array();
            foreach($models as $v){
                if (!$v->for_yur){
                    foreach($v->countries as $c){
                        if (isset($data[$c])){
                            $data[$c][] = $v;
                        } else {
                            $data[$c] = array($v);
                        }
                    }
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Получаем список событий по всем организациям.
     * @param bool $force_cache
     * @return Event[]
     */
    public function listModelsByAllOrg($force_cache=false)
    {
        $cache_id = __CLASS__.self::PREFIX_CACHE_LIST_MODELS_ALL_ORG;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false){
            $models = $this->listModels($force_cache);
            $data = array(
                'Организации' => array(),
                'Контрагенты' => array(),
            );
            foreach($models as $model){
                if ($model->for_yur){
                    $model->list_yur = $this->_parseListYur($model->list_yur);
                    foreach($model->list_yur as $yur){
                        if (isset($data[$yur['type_yur']][$yur['id_yur']])){
                            $data[$yur['type_yur']][$yur['id_yur']][] = $model;
                        } else {
                            $data[$yur['type_yur']][$yur['id_yur']] = array($model);
                        }
                    }
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Получаем список событий по всем организациям 2.
     * @param bool $force_cache
     * @return Event[]
     */
    public function listModelsAllOrganization($force_cache=false)
    {
        $cache_id = __CLASS__.self::PREFIX_CACHE_LIST_MODELS_ALL_ORGANIZATION;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false){
            $models = $this->listModels($force_cache);
            $data = array();
            foreach($models as $model){
                if ($model->for_yur){
                    $model->list_yur = $this->_parseListYur($model->list_yur);
                    $data[] = $model;
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * @param array $list_yur
     * @return array
     */
    private function _parseListYur(array $list_yur)
    {
        $list = array();
        if (isset($list_yur[0]) && is_array($list_yur[0])){
            for ($i = 0, $l=count($list_yur[0])/2; $i<$l; $i++){
                $type = 'type_yur'.$i;
                $id = 'id_yur'.$i;
                $list[] = array(
                    'id_yur' => $list_yur[0][$id],
                    'type_yur' => $list_yur[0][$type]
                );
            }
        }
        return $list;
    }

    /**
     * @param Event $a
     * @param Event $b
     * @return int
     */
    private function _sortEventDate(Event $a, Event $b)
    {
        $da = new DateTime($a->event_date);
        $db = new DateTime($b->event_date);
        if ($da == $db) {
            return 0;
        }
        return ($da < $db) ? -1 : 1;
    }
}