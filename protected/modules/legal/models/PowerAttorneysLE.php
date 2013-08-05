<?php
/**
 * Модель: Довереность.
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
class PowerAttorneysLE extends SOAPModel
{
    const PREFIX_CACHE_ID_LIST_DATA = '_list_org_id_';
    const PREFIX_CACHE_ID_LIST_ALL_DATA = '_list_all_data';
    const PREFIX_CACHE_ID_LIST_ALL_NAMES = '_list_all_names';

	public $owner_name = '';

	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return PowerAttorneysLE
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

	/**
	 * Список доверенностей
	 *
	 * @return PowerAttorneysLE[]
	 */
	public function findAll()
    {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listPowerAttorneyLE($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Доверенность
	 *
	 * @param $id
	 * @return bool|PowerAttorneysLE
	 * @internal param array $filter
	 */
	public function findByPk($id)
    {
		$ret = $this->SOAP->getPowerAttorneyLE(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
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
	 *  Удаление Доверенности
	 *
	 *  @return bool
	 */
	public function delete()
    {
		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deletePowerAttorneyLE(array('id' => $pk));
			return $ret->return;
		}
		return false;
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
		return array(
			'id'             => '#',
			'id_yur'         => 'Юр.лицо',
			'type_yur'       => 'Вид Юр.лица',

			'id_lico'        => 'На кого оформлена',
            'name'           => 'Название',
            'nom'            => 'Номер документа',
            'typ_doc'        => 'Вид',                  // см. getDocTypes()
            'date'           => 'Дата начала действия',
            'expire'         => 'Срок действия',
            'break'          => 'Недействительна с',
            'comment'        => 'Комментарий',

            'list_scans'     => 'Сканы',
            'list_files'     => 'Файлы',
            'upload_scans'   => '',
            'upload_files'   => '',

            // не исполозованные поля
            'e_ver'          => 'Файлы',
            'contract_types' => 'Виды договоров',
            'loaded'         => 'Дата загрузки документа',
            'user'           => 'Пользователь',
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
            array('id_lico', 'in', 'range'  => array_keys(Individuals::getValues())),

            array('typ_doc', 'required'),
            array('typ_doc', 'in', 'range'  => array_keys(PowerAttorneysLE::getDocTypes())),

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
     * @param Organization $org
     * @return PowerAttorneysLE[]
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
     * @return PowerAttorneysLE[]
     */
    public function getAllData(){
        $cache_id = get_class($this).self::PREFIX_CACHE_ID_LIST_ALL_DATA;
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false){
            $tmp = $this->where('deleted', false)
//                ->where('type_yur', 'Контрагенты')
                ->findAll();
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
     * @return array Format [id => name]
     */
    public function getAllNames(){
        $cache_id = get_class($this).self::PREFIX_CACHE_ID_LIST_ALL_NAMES;
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false){
            $tmp = $this->getAllData();
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
