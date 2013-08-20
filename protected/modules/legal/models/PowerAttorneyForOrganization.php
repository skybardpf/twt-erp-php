<?php
/**
 * Модель: Доверенность для организации.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @property array $type_of_contract
 */
class  PowerAttorneyForOrganization extends PowerAttorneyAbstract
{
    public $json_type_of_contract;

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

//    /**
//     *  Виды юр. лиц
//     *
//     *  @return array
//     */
//    public static function getYurTypes()
//    {
//        return array(
//            'Организации' => 'Организации',
//            'Контрагенты' => 'Контрагенты',
//        );
//    }

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels()
    {
		return array_merge(
            parent::attributeLabels(),
            array(
                'typ_doc'          => 'Вид',                  // см. getDocTypes()
                'type_of_contract' => 'Виды договора',
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
        return array_merge(
            parent::rules(),
            array(
                array('typ_doc', 'required'),
                array('typ_doc', 'in', 'range'  => array_keys(PowerAttorneyForOrganization::getDocTypes())),

                array('type_of_contract', 'required'),
            )
        );
	}
}
