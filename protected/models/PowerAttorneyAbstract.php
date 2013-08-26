<?php
/**
 * Общие свойства и методы для реализации модели "Довереность".
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $id             Идентификатор доверенности
 * @property string $id_yur         Идентификатор юрлица
 * @property string $type_yur       Тип юрлица ("Контрагенты", "Организации")
 * @property string $name           наименование
 *
 * @property string $nom            номер доверенности
 * @property string $typ_doc        вид доверенности («Генеральная», «Свободная», «ПоВидамДоговоров»)
 * @property string $id_lico        идентификатор физлица, на которое выписана доверенность
 * @property string $date           дата доверенности (дата)
 * @property string $expire         дата окончания действия доверенности (дата)
 * @property string $break          дата досрочного окончания действия доверенности (дата)
 * @property string $comment        комментрий
 *
 * @property bool   $deleted        флаг, удален ли данный документ
 * @property string $from_user      признак того, что доверенность загружена пользователем
 *
 * @property array  $list_scans     массив строк-ссылок на сканы доверенности
 * @property array  $list_files     массив строк-ссылок на файлы доверенности
 *
 * @property UploadDocument $uploadDocument
 * @method  bool upload(string $path, CUploadedFile $file)
 * @method  void removeFiles(string $path, array $files)
 * @method  void moveFiles(string $source, string $destination, array $files)
 */
abstract class PowerAttorneyAbstract extends SOAPModel
{
    const PREFIX_CACHE_ID_LIST_MODELS_FOR_ORG_ID = '_list_models_for_org_id_';
    const PREFIX_CACHE_ID_LIST_NAMES_FOR_ORG_ID = '_list_names_for_org_id_';

    public $upload_scans = array();
    public $upload_files = array(); //

    public $json_exists_scans;
    public $json_exists_files;

    public $owner_name = '';

    /**
     * Возвращает тип организации: Организация или контрагент.
     * @return string @see MTypeOrganization
     */
    abstract public function getTypeOrganization();

    /**
     *
     */
    protected function afterConstruct()
    {
        parent::afterConstruct();
        $this->attachBehaviors($this->behaviors());

        $this->type_yur = $this->getTypeOrganization();
        $this->from_user = true;
        $this->list_files = array();
        $this->list_scans = array();
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
	 * Список доверенностей
	 * @return PowerAttorneyAbstract[]
	 */
	protected function findAll()
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
	 * @param string $id
	 * @param bool $force_cache
	 * @return PowerAttorneyAbstract
	 */
	public function findByPk($id, $force_cache=false)
    {
        Yii::trace(get_class($this).'.findByPk()','SoapModel');
        $class = get_class($this);
        $cache_id = $class . self::PREFIX_CACHE_MODEL_PK . $id;
        if ($force_cache || ($model = Yii::app()->cache->get($cache_id)) === false){
            $data = $this->SOAP->getPowerAttorneyLE(array('id' => $id));
            $data = SoapComponent::parseReturn($data);
            $data = current($data);
            $model = $this->publish_elem($data, $class);
            if ($model === null) {
                throw new CHttpException(404, 'Доверенность не найдена.');
            }
            Yii::app()->cache->set($cache_id, $model);
        }
        $model->forceCached = $force_cache;
        return $model;
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
     * @return string Идентификатор доверености.
     */
    abstract public function save();

    /**
     * Сбрасываем кэши.
     */
    public function clearCache()
    {
        $class = get_class($this);
        if ($this->primaryKey){
            Yii::app()->cache->delete($class . self::PREFIX_CACHE_MODEL_PK . $this->primaryKey);
        }
        if ($this->id_yur){
            Yii::app()->cache->delete($class . self::PREFIX_CACHE_ID_LIST_NAMES_FOR_ORG_ID . $this->id_yur);
            Yii::app()->cache->delete($class . self::PREFIX_CACHE_ID_LIST_MODELS_FOR_ORG_ID . $this->id_yur);
        }
    }

    /**
     * Список доверенностей для указанной в $org_id организации.
     * @param string $org_id
     * @param bool $force_cache
     * @return PowerAttorneyAbstract[]
     */
    public function listModels($org_id, $force_cache = false)
    {
        $cache_id = get_class($this) . self::PREFIX_CACHE_ID_LIST_MODELS_FOR_ORG_ID . $org_id;
        if ($force_cache || ($models = Yii::app()->cache->get($cache_id)) === false){
            $models = $this
                ->where('deleted', false)
                ->where('type_yur', $this->getTypeOrganization())
                ->where('id_yur', $org_id)
                ->findAll();
            Yii::app()->cache->set($cache_id, $models);
        }
        return $models;
    }

    /**
     * Список наименований довереностей для указанной в $org_id организации.
     * @param string $org_id
     * @param bool $force_cache
     * @return array Format [id => name]
     */
    public function listNames($org_id, $force_cache = false)
    {
        $cache_id = get_class($this) . self::PREFIX_CACHE_ID_LIST_NAMES_FOR_ORG_ID . $org_id;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false){
            $data = array();
            $models = $this->listModels($org_id, $force_cache);
            foreach($models as $model){
                $data[$model->primaryKey] = $model->name;
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * @return array
     */
    public function attributeNames()
    {
        return array(
            'id',            // string
            'name',          // string
            'id_yur',        // string
            'type_yur',      // string
            'id_lico',       // string
            'nom',           // string
            'date',          // date
            'expire',        // date
            'break',         // date
            'comment',       // string
            'list_scans',    // array
            'list_files',    // array
            'from_user',     // bool
            'deleted',       // bool
        );
    }

    /**
     * Returns the list of attribute names of the model.
     * @return array list of attribute names.
     */
    public function attributeLabels()
    {
        return array(
            'id'             => '#',
            'id_yur'         => 'Юр.лицо',  // не изменяется
            'type_yur'       => 'Вид юр. лица',         // не изменяется
            'name'           => 'Наименование',

            'id_lico'        => 'На кого оформлена',
            'nom'            => 'Номер',

            'date'           => 'Дата начала действия',
            'expire'         => 'Срок действия',
            'break'          => 'Недействительна с',
            'comment'        => 'Комментарий',

            'list_scans'     => 'Сканы',
            'list_files'     => 'Файлы',

            'from_user'      => 'Загружен пользователем',
            'deleted'        => 'Помечен на удаление',
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
            array('id_lico', 'required'),
            array('id_lico', 'in', 'range'  => array_keys(Individual::model()->listNames($this->forceCached))),

            array('name', 'required'),
            array('name', 'length', 'max' => 25),

            array('nom', 'required'),
            array('nom', 'length', 'max' => 20),

            array('comment', 'length', 'max' => 50),

            array('date, expire', 'required'),
            array('date, expire, break', 'date', 'format' => 'yyyy-MM-dd'),

            array('list_scans', 'existsScans'),
            array('list_files', 'existsFiles'),

            array('json_exists_files', 'validJson'),
            array('json_exists_scans', 'validJson'),
        );
    }
}