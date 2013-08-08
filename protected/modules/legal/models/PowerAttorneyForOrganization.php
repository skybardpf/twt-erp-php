<?php
/**
 * Модель: Доверенность для организации.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @property string $id             Идентификатор доверенности
 * @property string $id_yur         Идентификатор юрлица
 * @property string $type_yur       Тип юрлица ("Контрагенты", "Организации")
 * @property string $nom            номер доверенности
 * @property string $typ_doc        вид доверенности («Генеральная», «Свободная», «ПоВидамДоговоров»)
 * @property string $id_lico        идентификатор физлица, на которое выписана доверенность
 * @property string $name           наименование
 * @property string $date           дата доверенности (дата)
 * @property string $expire         дата окончания действия доверенности (дата)
 * @property string $break          дата досрочного окончания действия доверенности (дата)
 * @property string $comment        комментрий
 * @property bool   $deleted        флаг, удален ли данный документ
 *
 * @property string $loaded         дата загрузки доверенности (дата)
 * @property string $e_ver          ссылка на электронную версию доверенности
 * @property string $contract_types массив строк-идентификаторов видов договоров, на которые распространяется доверенность
 *
 * @property array  $list_scans     массив строк-ссылок на сканы доверенности
 * @property array  $list_files     массив строк-ссылок на файлы доверенности
 *
 * @property string $from_user      признак того, что доверенность загружена пользователем
 * @property string $user           идентификатор пользователя
 */
class  PowerAttorneyForOrganization extends PowerAttorneyAbstract
{
	public $owner_name = '';

    /**
     * @return string
     */
    public function getTypeOrganization(){
        return MTypeOrganization::ORGANIZATION;
    }

	/**
	 * @static
	 * @param string $className
	 * @return  PowerAttorneyForOrganization
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

    /**
     *  Сохранение доверенности
     *
     *  @return array
     *  @throws CHttpException
     */
    public function save()
    {
        $data = $this->getAttributes();
        $data['user']       = SOAPModel::USER_NAME;
        $data['from_user']  = true;

        if (!$this->getprimaryKey()){
            unset($data['id']);
            $data['type_yur']  = 'Организации';
        }

        $doc_types = array(
            'Генеральная'       => 'Генеральная',
            'Свободная'         => 'Свободная',
            'По видам договоров'=> 'ПоВидамДоговоров'
        );
        $data['typ_doc'] = (isset($doc_types[$data['typ_doc']])) ? $doc_types[$data['typ_doc']] : $doc_types['Генеральная'];

        $upload_ids = array();
        if (!empty($this->upload_scans)) {
            foreach ($this->upload_scans as $f) {
                $uf = new UploadFile();
                $id = ($this->primaryKey) ? $this->primaryKey : 0;
                $id = $uf->upload($f, UploadFile::CLIENT_ID, __CLASS__, $id, UploadFile::TYPE_FILE_SCANS);
                if (!is_null($id)){
                    $upload_ids[] = $id;
                }
            }
        }
        if (!empty($this->upload_files)) {
            foreach ($this->upload_files as $f) {
                $uf = new UploadFile();
                $id = ($this->primaryKey) ? $this->primaryKey : 0;
                $id = $uf->upload($f, UploadFile::CLIENT_ID, __CLASS__, $id, UploadFile::TYPE_FILE_FILES);
                if (!is_null($id)){
                    $upload_ids[] = $id;
                }
            }
        }

        unset($data['deleted']);
        unset($data['list_scans']);
        unset($data['list_files']);
        unset($data['upload_scans']);
        unset($data['upload_files']);
        // unused
        unset($data['e_ver']);
        unset($data['contract_types']);
        unset($data['loaded']);

        $ret = $this->SOAP->savePowerAttorneyLE(array(
            'data' => array(
                'ElementsStructure' => SoapComponent::getStructureElement($data, array('lang' => 'eng')),
                'Tables' => array(
                    SoapComponent::getStructureActions($this),
                    SoapComponent::getStructureScans($this),
                    SoapComponent::getStructureFiles($this),
                )
            )
        ));
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
            }
        }
        return $ret;
    }

	/**
	 *  Виды доверенностей
	 *
	 *  @return array
	 */
	public static function getDocTypes()
    {
		return array(
			'Генеральная'       => 'Генеральная',
			'Свободная'         => 'Свободная',
			'По видам договоров'=> 'По видам договоров'
		);
	}

    /**
     *  Виды юр. лиц
     *
     *  @return array
     */
    public static function getYurTypes()
    {
        return array(
            'Организации' => 'Организации',
            'Контрагенты' => 'Контрагенты',
        );
    }

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels()
    {
        $parentLabels = parent::attributeLabels();
		return array_merge(
            $parentLabels,
            array(
                'typ_doc'           => 'Вид',                  // см. getDocTypes()
                'types_of_contract' => 'Виды договора',
            )
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
            array('id_lico', 'in', 'range'  => array_keys(Individuals::getValues())),

            array('typ_doc', 'required'),
            array('typ_doc', 'in', 'range'  => array_keys( PowerAttorneyForOrganization::getDocTypes())),

            array('name', 'required'),
            array('name', 'length', 'max' => 25),

            array('nom', 'length', 'max' => 20),
            array('comment', 'length', 'max' => 50),

            array('date, expire, break', 'date', 'format' => 'yyyy-MM-dd'),

            array('list_scans', 'existsScans'),
            array('list_files', 'existsFiles'),
		);
	}

    /**
     * Список довереностей.
     * @deprecated
     * @param Organization $org
     * @return  PowerAttorneyForOrganization[]
     */
    public function getData(Organization $org){
        $cache_id = get_class($this).self::PREFIX_CACHE_ID_LIST_DATA.$org->primaryKey;
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false){
            $data = $this->where('deleted', false)
                ->where('id_yur',  $org->primaryKey)
                ->where('type_yur', 'Организации')
                ->findAll();
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Список довереностей.
     * @deprecated
     * @param string $type
     * @param bool $force_cache
     * @return  PowerAttorneyForOrganization[]
     * @throws CException
     */
    public function getAllData($type, $force_cache = false){
        if (!in_array($type, array(Contractor::TYPE, Organization::TYPE))){
            throw new CException('Указан неизвестный тип организации.');
        }

        $cache_id = get_class($this).self::PREFIX_CACHE_ID_LIST_ALL_DATA.$type;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false){
            $tmp = $this->where('deleted', false)
                ->where('type_yur', $type)
                ->findAll();
            $data = array();
            if ($tmp){
                foreach($tmp as $v){
                    $data[$v->primaryKey] = $v;
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Список назавний довереностей.
     * @deprecated
     * @param string $type
     * @param string $org_id
     * @param bool $force_cache
     * @return array Format [id => name]
     * @throws CException
     */
    public function getNamesByOrganizationId($type, $org_id, $force_cache = false){
        if (!in_array($type, array(Contractor::TYPE, Organization::TYPE))){
            throw new CException('Указан неизвестный тип организации.');
        }
        $cache_id = get_class($this).self::PREFIX_CACHE_ID_LIST_ALL_NAMES.$type.'_'.$org_id;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false){
            $data = array();
            $tmp = $this->where('deleted', false)
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

    /**
     * Список назавний довереностей.
     * @deprecated
     * @param string $type
     * @param bool $force_cache
     * @return array Format [id => name]
     * @throws CException
     */
    public function getAllNames($type, $force_cache = false){
        if (!in_array($type, array(Contractor::TYPE, Organization::TYPE))){
            throw new CException('Указан неизвестный тип организации.');
        }

        $cache_id = get_class($this).self::PREFIX_CACHE_ID_LIST_ALL_NAMES.$type;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false){
            $data = array();
            $tmp = $this->getAllData($type, $force_cache);
            foreach($tmp as $v){
                $data[$v->primaryKey] = $v->name;
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     *  Валидатор. Проверяет, что имена файлов-сканов, которые хотят загрузить
     *  не совпадают с именами файлов, которые были загружены прежде.
     *
     *  @return void
     */
    public function existsScans(){
        if ($this->primaryKey && !empty($this->upload_scans)) {
            $arr = array();
            foreach ($this->upload_scans as $f) {
                $arr[] = '"'.$f->name.'"';
            }
            $cmd = Yii::app()->db->createCommand(
                'SELECT filename
                FROM '.UploadFile::model()->tableName().'
                WHERE filename IN ('.implode(',', $arr).')
                    AND client_id = :client_id AND model_name=:model_name AND model_id=:model_id AND type=:type'
            );
            $files = $cmd->queryAll(true, array(
                ':client_id'    => UploadFile::CLIENT_ID,
                ':model_name'   => __CLASS__,
                ':model_id'     => $this->primaryKey,
                ':type'         => UploadFile::TYPE_FILE_SCANS,
            ));
            foreach($files as $f){
                $this->addError('list_scans', 'Скан с таким именем уже существует: '.$f['filename']);
            }
        }
    }

    /**
     *  Валидатор. Проверяет, что имена файлов, которые хотят загрузить
     *  не совпадают с именами файлов, которые были загружены прежде.
     *
     *  @return void
     */
    public function existsFiles(){
        if ($this->primaryKey && !empty($this->upload_files)) {
            $arr = array();
            foreach ($this->upload_files as $f) {
                $arr[] = '"'.$f->name.'"';
            }
            $cmd = Yii::app()->db->createCommand(
                'SELECT filename
                FROM '.UploadFile::model()->tableName().'
                WHERE filename IN ('.implode(',', $arr).')
                    AND client_id=:client_id AND model_name=:model_name AND model_id=:model_id AND type=:type'
            );
            $files = $cmd->queryAll(true, array(
                ':client_id'    => UploadFile::CLIENT_ID,
                ':model_name'   => __CLASS__,
                ':model_id'     => $this->primaryKey,
                ':type'         => UploadFile::TYPE_FILE_FILES,
            ));
            foreach($files as $f){
                $this->addError('list_files', 'Файл с таким именем уже существует: '.$f['filename']);
            }
        }
    }
}
