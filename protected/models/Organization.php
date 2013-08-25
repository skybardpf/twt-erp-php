<?php
/**
 * Модель для работы с организацией.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
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
 * @property array  $signatories
 *
 * @property string $json_signatories
 */
class Organization extends OrganizationAbstract
{
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
	protected function findAll()
    {
        Yii::trace(get_class($this).'.findAll()','SoapModel');
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listOrganizations($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * @param string $id    Идентификатор организации.
     * @param bool $force_cache
     * @return Organization
     * @throws CHttpException
	 */
	public function findByPk($id, $force_cache=false)
    {
        Yii::trace(get_class($this).'.findByPk()','SoapModel');
        $cache_id = __CLASS__ . self::PREFIX_CACHE_MODEL_PK . $id;
        if ($force_cache || ($model = Yii::app()->cache->get($cache_id)) === false){
            $data = $this->SOAP->getOrganization(array('id' => $id));
            $data = SoapComponent::parseReturn($data);
            $data = current($data);
            $model = $this->publish_elem($data, __CLASS__);
            if ($model === null) {
                throw new CHttpException(404, 'Организация не найдена.');
            }
            Yii::app()->cache->set($cache_id, $model);
        }
        $model->forceCached = $force_cache;
        return $model;
	}

    /**
     * @return array
     */
    public function attributeNames()
    {
        return array(
            'id',               // string
            'country',          // string
            'country_name',     // string
            'name',             // string
            'full_name',        // string
            'sert_date',        // string
            'inn',              // string
            'kpp',              // string
            'ogrn',             // string
            'vat_nom',          // string
            'reg_nom',          // string
            'sert_nom',         // string
            'info',             // string
            'profile',          // string
            'yur_address',      // string
            'fact_address',     // string
            'email',            // string
            'phone',            // string
            'fax',              // string
            'comment',          // string
            'okopf',            // string
            'creation_date',    // date
            'creator',          // string
            'signatories',      // array
            'gendirector_id',   // string
            'deleted',          // bool

            'json_signatories', // string
        );
    }

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels()
    {
        return array(
            'id'            => '#',
            'country'       => 'Страна',
            'name'          => 'Наименование',
            'full_name'     => 'Полное наименование',
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
        );
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
    {
		return array(
			array('country', 'required'),
			array('country', 'in', 'range' => array_keys(Country::model()->listNames($this->forceCached))),

            array('okopf', 'required'),
            array('okopf', 'in', 'range' => array_keys(CodesOKOPF::model()->listNames($this->forceCached))),

            array('profile', 'required'),
            array('profile', 'in', 'range' => array_keys(ContractorTypesActivities::model()->listNames($this->forceCached))),

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

            /**modules.legal.
             * Foreign country
             */
            array('reg_nom, sert_nom, vat_nom', 'safe', 'on' => 'russianCountry'),
            array('vat_nom, reg_nom, sert_nom', 'length', 'max' => 50, 'on' => 'foreignCountry'),

            array('info, comment', 'length', 'max' => 50),
            array('yur_address, fact_address, fax, phone', 'length', 'max' => 150),

            array('email', 'ARuEmailValidator'),
            array('sert_date', 'date', 'format' => 'yyyy-MM-dd'),

            array('gendirector_id', 'required'),
            array('gendirector_id', 'in', 'range' => array_keys(ContactPersonForOrganization::model()->listNames($this->forceCached))),

            array('json_signatories', 'validJson'),
		);
	}
}