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

        // TODO как быть с не созданым документом
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

        if (!$this->primaryKey) {
            $upload_dir = Yii::app()->params->uploadDocumentDir;
            if (!ctype_digit($ret)){
                foreach($list_files as $f){
                    unlink($upload_dir.DIRECTORY_SEPARATOR.$path_files. DIRECTORY_SEPARATOR . $f);
                }
                foreach($list_scans as $f){
                    unlink($upload_dir.DIRECTORY_SEPARATOR.$path_scans. DIRECTORY_SEPARATOR . $f);
                }
            } else {
                $path = $upload_dir.DIRECTORY_SEPARATOR.Yii::app()->user->getId().DIRECTORY_SEPARATOR . __CLASS__ . DIRECTORY_SEPARATOR . $ret;
                $dest_scans = $path.DIRECTORY_SEPARATOR.MDocumentCategory::SCAN;
                $dest_files = $path.DIRECTORY_SEPARATOR.MDocumentCategory::FILE;
                if (!is_dir($path)){
                    mkdir($path, 0777, true);
                    mkdir($dest_scans, 0777);
                    mkdir($dest_files, 0777);
                }

                foreach($list_files as $f){
                    rename($upload_dir.DIRECTORY_SEPARATOR.$path_files.DIRECTORY_SEPARATOR.$f, $dest_files. DIRECTORY_SEPARATOR.$f);
                }
                foreach($list_scans as $f){
                    rename($upload_dir.DIRECTORY_SEPARATOR.$path_scans.DIRECTORY_SEPARATOR.$f, $dest_scans.DIRECTORY_SEPARATOR.$f);
                }
            }
        }
        $this->clearCache();

        return $ret;
    }
}
