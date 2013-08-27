<?php
/**
 * Учредительный документ
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $id         Идентификатор
 * @property string $id_yur     Юр.Лицо
 * @property string $type_yur   Тип юрлица ("Контрагенты", "Организации")
 * @property string $name       Название
 * @property string $num        Номер
 * @property string $comment    Комментарий
 * @property string $date       Дата
 * @property string $expire     Дата окончания
 * @property string $typ_doc    Тип документа (LEDocumentType)
 * @property string $deleted
 *
 * @property string $from_user  Создан пользователем
 * @property string $user       Пользователь
 *
 * @property array  $list_files
 * @property array  $list_scans
 *
 * @property UploadDocument $uploadDocument
 * @method  bool upload(string $path, CUploadedFile $file)
 * @method  void removeFiles(string $path, array $files)
 * @method  void moveFiles(string $source, string $destination, array $files)
 */
class FoundingDocument extends SOAPModel
{
    const PREFIX_CACHE_ID_LIST_DATA = '_list_org_id_';
    const PREFIX_CACHE_ID_FOR_MODEL_ID = '_model_id_';

    public $upload_scans = array();
    public $upload_files = array();

    public $json_exists_scans;
    public $json_exists_files;

	public $from_user = true;

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
	 * @static
	 * @param string $className
	 * @return FoundingDocument
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

    /**
     * @static
     * @param string $id
     * @param bool $force_cache
     * @return FoundingDocument
     * @throws CHttpException
     */
    public static function loadModel($id, $force_cache = false)
    {
        $class = self::model();
        $cache_id = get_class($class) . self::PREFIX_CACHE_ID_FOR_MODEL_ID . $id;
        if ($force_cache || ($model = Yii::app()->cache->get($cache_id)) === false){
            $model = $class->findByPk($id);
            if ($model === null) {
                throw new CHttpException(404, 'Не найден учредительный документ.');
            }
            Yii::app()->cache->set($cache_id, $model);
        }
        return $model;
    }

    /**
     * @param Organization $org
     * @return FoundingDocument
     * @throws CHttpException
     */
    public function createModel($org)
    {
        $this->id_yur    = $org->primaryKey;
        $this->type_yur  = "Организации";
        $this->from_user = true;
        $this->user      = SOAPModel::USER_NAME;
        $this->list_files = array();
        $this->list_scans = array();
        return $this;
    }

	/**
	 * Список учредительных документов
	 * @return FoundingDocument[]
	 */
	protected function findAll()
    {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listFoundingDocuments($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Учредительный документ
	 * @param $id
	 * @return FoundingDocument
	 */
	public function findByPk($id)
    {
        $data = $this->SOAP->getFoundingDocument(array('id' => $id));
        $data = SoapComponent::parseReturn($data);
        $data = current($data);
		return $this->publish_elem($data, __CLASS__);
	}

	/**
	 * Удаление учредительного документа
	 * @return bool
	 */
	public function delete()
    {
		if ($pk = $this->primaryKey) {
			$ret = $this->SOAP->deleteFoundingDocument(array('id' => $pk));
            $ret = SoapComponent::parseReturn($ret, false);
            if ($ret){
                $this->clearCache();
            }
            return $ret;
		}
		return false;
	}

	/**
	 * Сохранение учредительного документа
	 * @return array
	 */
	public function save()
    {
        $data = $this->getAttributes();

        if (!$this->primaryKey){
            unset($data['id']);
        }

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

        $ret = $this->SOAP->saveFoundingDocuments(array(
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
     * @return array
     */
    public function attributeNames()
    {
        return array(
            'id',           // string
            'name',         // string
            'id_yur',       // string
            'type_yur',     // string
            'num',          // string
            'comment',      // string
            'date',         // date
            'expire',       // date
            'typ_doc',      // string
            'deleted',      // bool
            'from_user',    // bool
            'user',         // string
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
			'type_yur'          => 'Тип Юр.лица',
			'name'              => 'Наименование',
			'num'               => 'Номер',
			'comment'           => 'Комментарий',
			'date'              => 'Дата начала действия',
			'expire'            => 'Срок действия',
			'typ_doc'           => 'Тип документа',
			'deleted'           => 'Помечен на удаление',
			'from_user'         => 'Добавлено пользователем',
			'user'              => 'Пользователь',

			'list_scans'        => 'Сканы',
			'list_files'        => 'Файлы'
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
    {
		return array(
            array('name', 'required'),
//            array('name', 'length', 'max' => 25),

            array('num', 'required'),
            array('num', 'length', 'max' => 10),

            array('typ_doc', 'required'),
            array('typ_doc', 'in', 'range'  => array_keys(LEDocumentType::model()->listNames($this->forceCached))),

            array('date, expire', 'required'),
            array('date, expire', 'date', 'format' => 'yyyy-MM-dd'),

            array('comment', 'length', 'max' => 50),

            array('list_scans', 'existsScans'),
            array('list_files', 'existsFiles'),

            array('json_exists_files', 'validJson'),
            array('json_exists_scans', 'validJson'),
		);
	}

    /**
     * Список учредительных документов
     * @param Organization $org
     * @param bool $force_cache
     * @return FoundingDocument[]
     */
    public function listModels(Organization $org, $force_cache=false)
    {
        $cache_id = __CLASS__.self::PREFIX_CACHE_ID_LIST_DATA.$org->primaryKey;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false){
            $data = $this->where('deleted', false)
                ->where('id_yur',  $org->primaryKey)
                ->where('type_yur', 'Организации')
                ->findAll();
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }
}