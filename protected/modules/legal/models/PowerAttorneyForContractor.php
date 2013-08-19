<?php
/**
 * Модель: Доверенность для контрагента.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class PowerAttorneyForContractor extends PowerAttorneyAbstract
{
    /**
     * @return string
     */
    public function getTypeOrganization(){
        return MTypeOrganization::CONTRACTOR;
    }

	/**
	 * @static
	 * @param string $className
	 * @return PowerAttorneyForContractor
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
        $data['typ_doc'] = '';

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

        $ret = $this->SOAP->savePowerAttorney(array(
            'data' => SoapComponent::getStructureElement($data),
            'list_files' => $list_files,
            'list_scans' => $list_scans,
            'type_of_contract' => array('Null') // только для организаций
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
}