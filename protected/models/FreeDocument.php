<?php
Yii::import('application.components.Behavior.UploadDocument');

/**
 * Свободный документ
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property int    $id
 * @property int    $id_yur
 * @property string $name
 * @property string $date
 * @property string $expire
 * @property string $type_yur
 * @property string $from_user
 * @property string $num
 * @property string $user
 * @property bool   $deleted
 *
 * @property array  $list_files
 * @property array  $list_scans
 *
 * @property UploadDocument $uploadDocument
 * @method  bool upload(string $path, CUploadedFile $file)
 * @method  void removeFiles(string $path, array $files)
 * @method  void moveFiles(string $source, string $destination, array $files)
 */
class FreeDocument extends SOAPModel
{
    const PREFIX_CACHE_ID_LIST_DATA = '_list_org_id_';
    const PREFIX_CACHE_ID_FOR_MODEL_ID = '_model_id_';

//    public $list_files = array();
//    public $list_scans = array();

    // Для внутренних нужд
    public $upload_scans = array();
    public $upload_files = array();
    public $json_exists_scans;
    public $json_exists_files;

    /**
     *
     */
    protected function afterConstruct()
    {
        $this->attachBehaviors($this->behaviors());
        parent::afterConstruct();
    }

    /**
	 * @static
	 * @param string $className
	 * @return FreeDocument
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
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
     * @static
     * @param string $id
     * @param bool $force_cache
     * @return FreeDocument
     * @throws CHttpException
     */
    public static function loadModel($id, $force_cache = false)
    {
        $class = self::model();
        $cache_id = get_class($class) . self::PREFIX_CACHE_ID_FOR_MODEL_ID . $id;
        if ($force_cache || ($model = Yii::app()->cache->get($cache_id)) === false){
            $model = $class->findByPk($id);
            if ($model === null) {
                throw new CHttpException(404, 'Не найден свободный документ.');
            }
            Yii::app()->cache->set($cache_id, $model);
        }
        $model->forceCached = $force_cache;
        return $model;
    }

    /**
     * @param Organization $org
     * @return FreeDocument
     * @throws CHttpException
     */
    public function createModel($org)
    {
        $this->id_yur    = $org->primaryKey;
        $this->type_yur  = "Организации";
        $this->from_user = true;
//        $this->user      = SOAPModel::USER_NAME;
        $this->user      = Yii::app()->user->getId();
        $this->list_files = array();
        $this->list_scans = array();
        return $this;
    }

	/**
	 * Список свободных документов
	 *
	 * @return FreeDocument[]
	 */
	public function findAll()
    {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listFreeDocuments($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Свободный документ
	 * @param   int $id
	 * @return  FreeDocument
	 */
	public function findByPk($id)
    {
		$ret = $this->SOAP->getFreeDocument(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

    /**
     * Удаление Свободного документа
     * @return bool
     */
    public function delete()
    {
        if ($pk = $this->primaryKey) {
            $ret = $this->SOAP->deleteFreeDocument(array('id' => $pk));
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
            Yii::app()->cache->delete($class . self::PREFIX_CACHE_ID_FOR_MODEL_ID . $this->primaryKey);
        }
        if ($this->id_yur){
            Yii::app()->cache->delete($class . self::PREFIX_CACHE_ID_LIST_DATA . $this->id_yur);
        }
    }

	/**
	 * Сохранение свободного документа
	 * @return array
	 */
	public function save()
    {
        $data = $this->getAttributes();

        if (!$this->primaryKey){
            unset($data['id']);
        }
        $data['type_yur']   = 'Организации';
//        $data['user']       = SOAPModel::USER_NAME;
        $data['user']       = Yii::app()->user->getId();
        $data['from_user']  = true;

        $list_scans = array();
        $list_files = array();

        $id = ($this->primaryKey) ? $this->primaryKey : 'tmp_id';

        $path = Yii::app()->user->getId(). DIRECTORY_SEPARATOR . __CLASS__ . DIRECTORY_SEPARATOR . $id;
        $path_scans = $path . DIRECTORY_SEPARATOR . MDocumentCategory::SCAN;
        $path_files = $path . DIRECTORY_SEPARATOR . MDocumentCategory::FILE;

        foreach ($this->upload_scans as $f) {
            if ($this->upload($path_scans, $f)){
                $list_scans[] = $f->name;
            }
        }
        foreach ($this->upload_files as $f) {
            if ($this->upload($path_files, $f)){
                $list_files[] = $f->name;
            }
        }

        $list_files = array_merge($list_files, $this->list_files);
        $list_scans = array_merge($list_scans, $this->list_scans);

        $list_files = (empty($list_files)) ? array('Null') : $list_files;
        $list_scans = (empty($list_scans)) ? array('Null') : $list_scans;

        unset($data['deleted']);
        unset($data['list_scans']);
        unset($data['list_files']);
        unset($data['upload_scans']);
        unset($data['upload_files']);

        $ret = $this->SOAP->saveFreeDocument(array(
            'data' => SoapComponent::getStructureElement($data),
            'list_files' => $list_files,
            'list_scans' => $list_scans,
        ));
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
                    $this->removeFiles($path_scans, $list_scans);
                } else {
                    $path = Yii::app()->user->getId()
                        .DIRECTORY_SEPARATOR . __CLASS__
                        .DIRECTORY_SEPARATOR . $ret;
                    $dest_scans = $path.DIRECTORY_SEPARATOR.MDocumentCategory::SCAN;
                    $dest_files = $path.DIRECTORY_SEPARATOR.MDocumentCategory::FILE;

                    $this->moveFiles($path_files, $dest_files, $list_files);
                    $this->moveFiles($path_scans, $dest_scans, $list_scans);
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
     * @return array
     */
    public function attributeNames()
    {
        return array(
            'id',           // string
            'deleted',      // bool
            'from_user',    // bool
            'user',         // string
            'name',         // string
            'id_yur',       // string
            'type_yur',     // string
            'num',          // string
            'date',         // date
            'expire',       // date
            'comment',      // string
            'list_scans',   // array
            'list_files',   // array
        );
    }

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels()
    {
		return array(
            'id'                => '#',
            'id_yur'            => 'Юр.Лицо',
            'type_yur'          => 'Тип юр. лица',
            'from_user'         => 'От пользователя',
            'user'              => 'Пользователь',
            'deleted'           => 'Помечен на удаление',

            'num'               => 'Номер',
            'name'              => 'Наименование',
            'date'              => 'Дата начала действия',
            'expire'            => 'Срок действия',
            'comment'           => 'Комментарий',

            'list_files'           => 'Файлы',
            'list_scans'           => 'Сканы',
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
    {
		return array(
            array('name', 'required'),
			array('name', 'length', 'max' => 25),

            array('num', 'required'),
            array('num', 'length', 'max' => 50),

            array('date, expire', 'date', 'format' => 'yyyy-MM-dd'),
            array('comment', 'length', 'max' => 50),

            array('list_scans', 'existsScans'),
            array('list_files', 'existsFiles'),

            array('json_exists_files', 'validJson'),
            array('json_exists_scans', 'validJson'),
		);
	}

    /**
     * Список свободных документов.
     * @param Organization $org
     * @param bool $force_cache
     * @return FreeDocument[]
     */
    public function listModels(Organization $org, $force_cache=false)
    {
        $cache_id = get_class($this).self::PREFIX_CACHE_ID_LIST_DATA.$org->primaryKey;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false){
            $data = $this->where('deleted', false)
                ->where('id_yur',  $org->primaryKey)
                ->findAll();
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }
}
