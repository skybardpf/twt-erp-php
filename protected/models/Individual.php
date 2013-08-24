<?php
/**
 * Модель: Физические лица.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @property string $id             Идентификатор
 * @property bool   $deleted        Удален? ("true" - да, "false" - нет)
 *
 * @property string $family         Фамилия
 * @property string $name           Имя
 * @property string $parent_name    Отчество
 * @property string $citizenship    Гражданство
 * @property string $birth_place    Место рождения
 * @property string $birth_date     Дата рождения
 *
 * @property string $phone          Контактные данные
 * @property string $email
 * @property string $adres          Адрес прописки
 * @property string $ser_nom_pass   Серия и номер удостоверения
 * @property string $date_pass      Дата выдачи удостоверения
 * @property string $organ_pass     Орган, выдавший удостоверение
 * @property string $date_exp_pass  Срок действия удостоверения
 */
class Individual extends SOAPModel {
    const PREFIX_CACHE_ID_LIST_FULL_DATA = '_list_full_data';
    const PREFIX_CACHE_ID_LIST_FIO = '_list_fio';

	/**
	 * @static
	 * @param string $className
	 * @return Individual
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

    /**
     * @static
     * @param string $id
     * @param bool $force_cache
     * @return Individual
     * @throws CHttpException
     */
    public static function loadModel($id, $force_cache = false)
    {
        $cache_id = __CLASS__.'_'.$id;
//        $model = Yii::app()->cache->get($cache_id);
        if ($force_cache || ($model = Yii::app()->cache->get($cache_id)) === false){
            $model = self::model()->findByPk($id);
            if ($model === null) {
                throw new CHttpException(404, 'Не найдено физическое лицо.');
            }
            Yii::app()->cache->set($cache_id, $model);
        }
        return $model;
    }

    /**
     * Сбрасываем кеш по данному физ. лицу и для списков физ. лиц.
     */
    public function clearCache()
    {
        if ($this->primaryKey){
            Yii::app()->cache->delete(__CLASS__.'_'.$this->primaryKey);
        }
        Yii::app()->cache->delete(__CLASS__.self::PREFIX_CACHE_ID_LIST_FULL_DATA);
        Yii::app()->cache->delete(__CLASS__.self::PREFIX_CACHE_ID_LIST_FIO);
    }

	/**
	 * Список Физ.лиц
	 * @return Individual[]
	 */
	public function findAll() {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listIndividuals($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Получаем модель "Физ. лицо" по его $id.
	 *
	 * @param string $id
	 * @return Individual
	 */
	public function findByPk($id) {
        $data = $this->SOAP->getIndividual(array('id' => $id));
        $data = SoapComponent::parseReturn($data);
        $data = current($data);
		return $this->publish_elem($data, __CLASS__);
	}

    /**
     * Сохранение Физ.Лица
     * @return array
     */
    public function save() {
        $attr = $this->getAttributes();
        if (!$this->primaryKey) {
            unset($attr['id']);
        }
        unset($attr['deleted']);

        $ret = $this->SOAP->saveIndividual(array(
            'data' => SoapComponent::getStructureElement($attr, array('convert_boolean' => true))
        ));
        $ret = SoapComponent::parseReturn($ret, false);
        $this->clearCache();
        return $ret;
    }

	/**
	 * Удаление Физ.лица
	 * @return bool
	 */
	public function delete() {
		if ($id = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteIndividual(array('id' => $id));
            $ret = SoapComponent::parseReturn($ret, false);
            if ($ret){
                $this->clearCache();
            }
            return $ret;
		}
        return false;
	}
    
	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
        return array(
            'id'              => '#',
            'name'            => 'Имя',
            'family'          => 'Фамилия',
            'parent_name'     => 'Отчество',

            'citizenship'     => 'Гражданство',
            
            'birth_date'      => 'Дата рождения',
            'birth_place'     => 'Место рождения',

            'phone'           => 'Контактные данные',
            'email'           => 'E-mail',
            'adres'           => 'Адрес прописки',

            'ser_nom_pass'    => 'Серия и номер удостоверения',
            'date_pass'       => 'Дата выдачи удостоверения',
            'organ_pass'      => 'Орган, выдавший удостоверение',
            'date_exp_pass'   => 'Срок действия удостоверения',

            'deleted'         => 'Помечен на удаление',
        );
	}

	/**
	 * Список доступных ФИО физ.лиц. [id => name]
     * @deprecated
	 * @return array
	 */
	public static function getValues() {
        $cache_id = __CLASS__.self::PREFIX_CACHE_ID_LIST_FIO;
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false) {
            $elements = self::model()->findAll();
            $data = array();
            if ($elements) {
                foreach ($elements as $elem) {
                    $data[$elem->primaryKey] = $elem->family .' '.$elem->name.' '.$elem->parent_name;
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
	}

    /**
     * Список доступных ФИО физ.лиц. [id => name]
     * @param bool $force_cache
     * @return array
     */
    public static function getDataNames($force_cache = false)
    {
        $cache_id = __CLASS__.self::PREFIX_CACHE_ID_LIST_FIO;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false) {
            $data = array();
            $elements = self::model()->findAll();
            if ($elements) {
                foreach ($elements as $elem) {
                    $data[$elem->primaryKey] = $elem->family .' '.$elem->name.' '.$elem->parent_name;
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Список доступных физических лиц. Формат [family + key] = element
     * @deprecated @see getData()
     * @return array
     */
    public static function getFullValues() {
        $cache_id = __CLASS__.self::PREFIX_CACHE_ID_LIST_FULL_DATA;
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false) {
            $elements = self::model()
                ->where('deleted', false)
                ->findAll();
            $data = array();
            if ($elements) {
                $tmp = array();
                foreach ($elements as $elem) {
                    $tmp[$elem->family.'_'.$elem->primaryKey] = $elem;
                }
                ksort($tmp);
                foreach ($tmp as $elem) {
                    $data[] = $elem;
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Список физических лиц.
     * @param bool $force_cache
     * @return Individual[]
     */
    public function getData($force_cache = false) {
        $cache_id = __CLASS__.self::PREFIX_CACHE_ID_LIST_FULL_DATA;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false) {
            $data = array();
            $elements = self::model()->where('deleted', false)->findAll();
            if ($elements) {
                $tmp = array();
                foreach ($elements as $elem) {
                    $tmp[$elem->family.'_'.$elem->primaryKey] = $elem;
                }
                ksort($tmp);
                foreach ($tmp as $elem) {
                    $data[] = $elem;
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Валидация полей при сохранении.
     * @return array
     */
    public function rules()
    {
		return array(
            array('citizenship', 'required'),
            array('citizenship', 'in', 'range'  => array_keys(Countries::model()->getDataNames($this->getForceCached()))),

            array('name, family', 'required'),
            array('name, family, parent_name', 'length', 'max' => 50),

            array('phone', 'length', 'max' => 100),
            array('birth_place, adres', 'length', 'max' => 150),
            array('organ_pass', 'length', 'max' => 100),
            array('ser_nom_pass', 'length', 'max' => 50),

            array('birth_date, date_exp_pass, date_pass', 'date', 'format' => 'yyyy-MM-dd', 'message' => "Поле {attribute} имеет неправильный формат даты. Либо дата больше 2038-01-19"),
//            array('birth_date, date_exp_pass, date_pass', 'validDate'),

            array('email', 'ARuEmailValidator'),
		);
	}

    public function validDate($attribute)
    {
        if (!empty($this->$attribute) && strtotime($this->$attribute) === false){
            $this->addError($attribute, 'Поле {$attribute} имеет неправильный формат даты. Либо дата больше 2038-01-19');
        }
    }
}