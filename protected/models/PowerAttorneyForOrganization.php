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
    const TYPE_DOC_GENERAL = 'Генеральная';

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
     *  @return array
     *  @throws CHttpException
     */
    public function save()
    {
        $data = $this->getAttributes();

        if (!$this->primaryKey){
            unset($data['id']);
        }
        $data['from_user'] = true;
        $doc_types = array(
            'Генеральная'       => 'Генеральная',
            'Свободная'         => 'Свободная',
            'По видам договоров'=> 'ПоВидамДоговоров'
        );
        $data['typ_doc'] = (isset($doc_types[$data['typ_doc']])) ? $doc_types[$data['typ_doc']] : $doc_types['Генеральная'];

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
        unset($data['json_type_of_contract']);
        unset($data['json_exists_scans']);
        unset($data['json_exists_files']);
        unset($data['type_of_contract']);

        $ret = $this->SOAP->savePowerAttorney(array(
            'data' => SoapComponent::getStructureElement($data),
            'list_files' => $list_files,
            'list_scans' => $list_scans,
            'type_of_contract' => (empty($this->type_of_contract)) ? array('Null') : $this->type_of_contract
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

                array('type_of_contract', 'required', 'on'=>'typeDocNotGeneral'),

                array('json_type_of_contract', 'validJson'),
            )
        );
	}
}
