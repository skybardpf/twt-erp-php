<?php
/**
 * User: Forgon
 * Date: 02.04.13
 *
 * @property string $id             Идентификатор доверенности
 *
 * @property string $id_yur         Идентификатор организации
 * @property string $name           наименование
 * @property string $date           дата доверенности (дата)
 * @property string $from_user      признак того, что доверенность загружена пользователем
 * @property string $user           идентификатор пользователя
 * @property string $nom            номер доверенности
 * @property string $typ_doc        вид доверенности («Генеральная», «Свободная», «ПоВидамДоговоров»)
 * @property string $id_lico        идентификатор физлица, на которое выписана доверенность
 * @property string $loaded         дата загрузки доверенности (дата)
 * @property string $expire         дата окончания действия доверенности (дата)
 * @property string $break          дата досрочного окончания действия доверенности (дата)
 * @property string $e_ver          ссылка на электронную версию доверенности
 * @property string $comment        комментрий
 * @property string $contract_types массив строк-идентификаторов видов договоров, на которые распространяется доверенность
 * @property string $scans          массив строк-ссылок на сканы доверенности
 */
class PowerAttorneysLE extends SOAPModel {

	public $from_user = true;
	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return PowerAttorneysLE
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * Список доверенностей
	 *
	 * @return PowerAttorneysLE[]
	 */
	public function findAll() {
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
	public function findByPk($id) {
		$ret = $this->SOAP->getPowerAttorneyLE(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}
    
    /**
     * Сохранение доверенности
     * @return array
     */
    public function save() {
        $cacher = new CFileCache();
        $cacher->set('PowerAttorneysLE_values', false, 1);

        $attrs = $this->getAttributes();

        $attrs['from_user'] = intval($attrs['from_user']) ? 'true' : 'false';
        
        if (!$this->getprimaryKey()) $attr['id'] = ''; //unset($attrs['id']); // New record
        unset($attrs['deleted']);

        $ret = $this->SOAP->savePowerAttorneyLE(array('data' => $attrs));
        $ret = SoapComponent::parseReturn($ret, false);
        return $ret;
    }
	/**
	 * Удаление Доверенности
	 *
	 * @return bool
	 */
	public function delete() {
		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deletePowerAttorneyLE(array('id' => $pk));
			return $ret->return;
		}
		return false;
	}

	public static function getDocTypes(){
		return array(
			'Генеральная'       => 'Генеральная',
			'Свободная'         => 'Свободная',
			'ПоВидамДоговоров'  => 'По видам договоров'
		);
	}

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
		return array(
			'id'             => '#',
			'id_lico'        => 'На кого оформлена',
            'name'           => 'Наименование',
            'typ_doc'        => 'Вид доверенности',                  // + (выбор из списка: генеральная, по видам договоров, свободная; обязательное);
            'date'           => 'Дата начала действия',
            'expire'         => 'Срок действия',
            // нету комментариев
            'scans'          => 'Сканы',
            'e_ver'          => 'Файлы',
			'comment'        => 'Комментарий',
            
            // не исполозованные поля
            'id_yur'         => 'Юр.лицо',
			'loaded'         => 'Дата загрузки документа',
			'break'          => 'Дата отмены',
			'user'           => 'Пользователь',
			'nom'            => 'Номер документа',
			'deleted'        => 'Помечен на удаление',
			'from_user'      => 'Загружен пользователем',
			'contract_types' => 'Виды договоров',
		);
	}
}
