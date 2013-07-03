<?php
/**
 *  User: Skibardin A.A.
 *  Date: 27.06.13
 *
 * @property string $id             Идентификатор доверенности
 *
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
 * @property string $scans          массив строк-ссылок на сканы доверенности
 *
 * @property string $from_user      признак того, что доверенность загружена пользователем
 * @property string $user           идентификатор пользователя
 */
class PowerAttorneysLE extends SOAPModel {

	public $from_user = true;
	public $owner_name = '';

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
     *  Сохранение доверенности
     *
     *  @return array
     */
    public function save() {
        $data = $this->getAttributes();

        if (!$this->getprimaryKey()){
            unset($data['id']);
            $data['type_yur'] = 'Организации';
        }
        unset($data['deleted']);
//        unset($data['file']); // TODO когда появятся файлы
//        $data['type_yur']   = 'Организации';
//        (isset($this->_aTypeYur[$data['type_yur']])) ? $this->_aTypeYur[$data['type_yur']] : $this->_aTypeYur[0];
        $data['user']       = SOAPModel::USER_NAME;
        $data['from_user']  = true;

        unset($data['scans']);
        unset($data['e_ver']);
        unset($data['contract_types']);
        unset($data['loaded']);

        $doc_types = array(
            'Генеральная'       => 'Генеральная',
            'Свободная'         => 'Свободная',
            'По видам договоров'=> 'ПоВидамДоговоров'
        );

        $data['typ_doc'] = (isset($doc_types[$data['typ_doc']])) ? $doc_types[$data['typ_doc']] : $doc_types['Генеральная'];

        $arr = array(
            'ElementsStructure' => SoapComponent::getStructureElement($data, array('lang' => 'eng')),
            'Tables' => array(
                array(
                    "Name" => "СписокДействий",
                    "Value" => array(
                        'column' => array(),
                        'index' => array(),
                        'row'   => array(),
                    )
                ),

                array(
                    "Name" => "Сканы",
                    "Value" => array(
                        'column' => array(),
                        'index' => array(),
                        'row' => array(),
                    )
                ),
                array(
                    "Name" => "Файлы",
                    "Value" => array(
                        'column' => array(),
                        'index' => array(),
                        'row' => array(),
                    )
                ),

            )
        );
        $ret = $this->SOAP->savePowerAttorneyLE(array(
            'data' => $arr
        ));
        $ret = SoapComponent::parseReturn($ret, false);
        return $ret;
    }
	/**
	 *  Удаление Доверенности
	 *
	 *  @return bool
	 */
	public function delete() {
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
	public static function getDocTypes(){
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
    public static function getYurTypes(){
        return array(
            'Организации' => 'Организации',
            'Контрагенты' => 'Контрагенты',
        );
    }

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
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

            // не исполозованные поля
            'scans'          => 'Сканы',
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
		);
	}
}
