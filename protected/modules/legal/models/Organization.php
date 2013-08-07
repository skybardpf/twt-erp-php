<?php
/**
 * Модель для работы с организацией.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @property string $id
 * @property string $name
 * @property string $full_name
 * @property string $country
 * @property string $country_name
 * @property string $gendirector_id
 * @property string $comment
 * @property string $inn
 * @property string $kpp
 * @property string $ogrn
 * @property string $yur_address
 * @property string $fact_address
 * @property string $reg_nom
 * @property string $sert_nom
 * @property string $vat_nom
 * @property string $profile
 * @property string $deleted
 *
 * @property array  $signatories
 * @property string $json_signatories   // private
 */
class Organization extends AbstractOrganization {
    const TYPE = 'Организации';

	/**
	 * @static
	 * @param string $className
	 * @return Organization
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

	/**
	 * Удаляем организацию.
	 * @return bool
	 */
	public function delete()
    {
		if ($pk = $this->primaryKey) {
            $ret = $this->SOAP->deleteOrganization(array('id' => $pk));
            $ret = SoapComponent::parseReturn($ret, false);
            if ($ret){
                $this->clearCache();
            }
            return $ret;
		}
        return false;
	}

	/**
	 * Сохранение организации.
	 * @return array
	 */
	public function save()
    {
		$data = $this->getAttributes();

        /*if($data['sert_date'] == ''){
            $data['sert_date'] = date('Y-m-d', 0);
        }*/

		// New record
		if (!$this->primaryKey) {
            unset($data['id']);
            $data['creation_date'] = date('Y-m-d');
            $data['creator'] = 'Малхасян'; // TODO изменить, когда будет авторизация
        }
		unset($data['deleted']);
        unset($data['signatories']);
        unset($data['json_signatories']);

        if ($data['country'] == self::COUNTRY_RUSSIAN_ID){
            $data['vat_nom'] = '';
            $data['reg_nom'] = '';
            $data['sert_nom'] = '';
        } else {
            $data['inn'] = '';
            $data['kpp'] = '';
            $data['ogrn'] = '';
        }

        $ret = $this->SOAP->saveOrganization(
            array(
                'data' => SoapComponent::getStructureElement($data, array('convert_boolean' => true)),
                'signatories' => $this->signatories
            )
        );

        $ret = SoapComponent::parseReturn($ret, false);
        /**
         * Очищаем кеши связанные с организацией.
         */
        $this->clearCache();

        return $ret;
	}

	/**
	 * Список Собственных Юр.Лиц
	 *
	 * @return Organization[]
	 */
	public function findAll()
    {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listOrganizations($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Собственное Юр.Лицо
	 * @param string $id
	 * @return Organization
	 */
	public function findByPk($id)
    {
        $data = $this->SOAP->getOrganization(array('id' => $id));
        $data = SoapComponent::parseReturn($data);
        $data = current($data);
		return $this->publish_elem($data, __CLASS__);
	}

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels()
    {
        return array(
            "id"            => '#',
            "country"       => 'Страна',    // id
            "country_name"  => '',

            "name"          => 'Наименование',
            "full_name"     => 'Полное наименование',
            'sert_date'     => 'Дата государственной регистрации',
            'inn'           => 'ИНН',
            'kpp'           => 'КПП',
            'ogrn'          => 'ОГРН',
            'vat_nom'       => 'VAT',
            'reg_nom'       => 'Регистрационный номер',
            'sert_nom'      => 'Номер сертификата',
            'info'          => 'Дополнительная информация',
            'profile'       => 'Основной вид деятельности',
            'yur_address'   => 'Юридический адрес',
            'fact_address'  => 'Фактический адрес',
            'email'         => 'Email',
            'phone'         => 'Телефон',
            'fax'           => 'Факс',
            'comment'       => 'Комментарий',
            'okopf'         => 'Организационно-правовая форма',

            'creation_date' => 'Дата создания',
            'creator'       => 'Пользователь, добавивший в систему',

            'signatories'   => 'Подписанты',
            'gendirector_id' => 'Генеральный директор',

            'json_signatories' => '', // private

            'deleted'       => 'Помечен на удаление',                // +

        );
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
    {
		return array(
			array('country', 'required'),
			array('country', 'in', 'range' => array_keys(Countries::getValues())),

            array('okopf', 'required'),
            array('okopf', 'in', 'range' => array_keys(CodesOKOPF::getValues())),

            array('profile', 'required'),
            array('profile', 'in', 'range' => array_keys(ContractorTypesActivities::getValues())),

			array('name, full_name', 'required'),
            array('name', 'length', 'max' => 50),
            array('full_name', 'length', 'max' => 100),

            /**
             * Russian country
             */
            array('inn, kpp, ogrn', 'safe', 'on' => 'foreignCountry'),
            array('inn', 'validateInn', 'on' => 'russianCountry'),
            array('ogrn', 'validateOgrn', 'on' => 'russianCountry'),
            array('kpp', 'length', 'max' => 9, 'on' => 'russianCountry'),

            /**
             * Foreign country
             */
            array('reg_nom, sert_nom, vat_nom', 'safe', 'on' => 'russianCountry'),
            array('vat_nom, reg_nom, sert_nom', 'length', 'max' => 50, 'on' => 'foreignCountry'),

            array('info, comment', 'length', 'max' => 50),
            array('yur_address, fact_address, fax, phone', 'length', 'max' => 150),

            array('email', 'ARuEmailValidator'),
            array('sert_date', 'date', 'format' => 'yyyy-MM-dd'),

            array('gendirector_id', 'required'),
            array('gendirector_id', 'in', 'range' => array_keys(ContactPersonForOrganization::model()->getDataNames())),

            array('json_signatories', 'validJson'),
		);
	}
}