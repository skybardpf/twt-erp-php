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

//        $upload_ids = array();
//        if (!empty($this->upload_scans)) {
//            foreach ($this->upload_scans as $f) {
//                $uf = new UploadFile();
//                $id = ($this->primaryKey) ? $this->primaryKey : 0;
//                $id = $uf->upload($f, UploadFile::CLIENT_ID, __CLASS__, $id, UploadFile::TYPE_FILE_SCANS);
//                if (!is_null($id)){
//                    $upload_ids[] = $id;
//                }
//            }
//        }
//        if (!empty($this->upload_files)) {
//            foreach ($this->upload_files as $f) {
//                $uf = new UploadFile();
//                $id = ($this->primaryKey) ? $this->primaryKey : 0;
//                $id = $uf->upload($f, UploadFile::CLIENT_ID, __CLASS__, $id, UploadFile::TYPE_FILE_FILES);
//                if (!is_null($id)){
//                    $upload_ids[] = $id;
//                }
//            }
//        }

        unset($data['deleted']);
        unset($data['list_scans']);
        unset($data['list_files']);
        unset($data['upload_scans']);
        unset($data['upload_files']);

        $list_files = array('Null');
        $list_scans = array('Null');

        $ret = $this->SOAP->savePowerAttorney(array(
            'data' => SoapComponent::getStructureElement($data),
            'list_files' => $list_files,
            'list_scans' => $list_scans,
            'type_of_contract' => array('Null') // только для организаций
        ));
        $ret = SoapComponent::parseReturn($ret, false);

//        if (!$this->primaryKey) {
//            if (!ctype_digit($ret)){
//                foreach($upload_ids as $id){
//                    $uf = new UploadFile();
//                    $uf->delete_file($id);
//                }
//            } else {
//                foreach($upload_ids as $id){
//                    $uf = new UploadFile();
//                    $uf->move($id, $ret);
//                }
//            }
//        }
        $this->clearCache();

        return $ret;
    }
}
