<?php
/**
 * Общие методы и свойства для организаций и контрагентов.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
abstract class AbstractOrganization extends SOAPModel {
    const COUNTRY_RUSSIAN_ID = 643;

    const PREFIX_CACHE_ID_LIST_INN = '_list_inn';
    const PREFIX_CACHE_ID_LIST_OGRN = '_list_ogrn';
    const PREFIX_CACHE_ID_LIST_FULL_DATA = '_list_full_data';
    const PREFIX_CACHE_ID_LIST_NAMES = '_list_names';

    /**
     *  Список организаций|контаргентов.
     *  @return array [id => Model]
     */
    public function getFullData() {
        $cache_id = get_class($this).self::PREFIX_CACHE_ID_LIST_FULL_DATA;
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false) {
            $elements = $this->where('deleted', false)->findAll();
            $data = array();
            if ($elements) {
                foreach ($elements as $elem) {
                    $data[] = $elem;
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     *  Список названий организаций|контаргентов.
     *  @return array Формат [id => name]
     */
    public function getListNames() {
        $cache_id = get_class($this).self::PREFIX_CACHE_ID_LIST_NAMES;
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false) {
            $elements = $this->getFullData();
            $data = array();
            foreach ($elements as $elem) {
                $data[$elem->primaryKey] = $elem->name;
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     *  Список уже существующих ИНН.
     *  @return array Формат [inn => id]
     */
    public function listInn() {
        $cache_id = get_class($this).self::PREFIX_CACHE_ID_LIST_INN;
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false) {
            $elements = $this->getFullData();
            $data = array();
            foreach ($elements as $elem) {
                if (!empty($elem->inn)){
                    $data[$elem->inn] = $elem->primaryKey;
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     *  Список уже существующих ОГРН.
     *  @return array
     */
    public function listOgrn() {
        $cache_id = get_class($this).self::PREFIX_CACHE_ID_LIST_OGRN;
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false) {
            $elements = $this->getFullData();
            $data = array();
            foreach ($elements as $elem) {
                if (!empty($elem->ogrn)){
                    $data[$elem->ogrn] = $elem->primaryKey;
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Валидация ИНН.
     * @param string $attribute
     */
    public function validateInn($attribute)
    {
        if (!empty($this->$attribute)){
            if (!$this->isValidInn($this->$attribute)){
                $this->addError($attribute, 'Неправильный формат ИНН.');
            } else {
                $list = $this->listInn();
                var_dump($list);
//                var_dump($list[$this->$attribute]);

                if ($this->primaryKey){
                    if (isset($list[$this->$attribute]) && ($list[$this->$attribute] != $this->primaryKey)){
                        $this->addError($attribute, 'Такой ИНН уже используется другой организацией.');
                    }
                } elseif (isset($list[$this->$attribute])){
                    $this->addError($attribute, 'Такой ИНН уже используется другой организацией.');
                }
            }
        }
    }

    /**
     * Валидация ОГРН.
     * @param string $attribute
     */
    public function validateOgrn($attribute)
    {
        if (!empty($this->$attribute)){
            $msg = $this->isValidOgrn($this->$attribute);
            if (!empty($msg)){
                $this->addError($attribute, $msg);
            } else {
                $list = $this->listOgrn();
                if ($this->primaryKey){
                    if (isset($list[$this->$attribute]) && ($list[$this->$attribute] != $this->primaryKey)){
                        $this->addError($attribute, 'Такой ОГРН уже используется другой организацией.');
                    }
                } elseif (isset($list[$this->$attribute])){
                    $this->addError($attribute, 'Такой ОГРН уже используется другой организацией.');
                }
            }
        }
    }

    /**
     * Сбрасываем кеш по данной организации, для списка организаций, списка ИНН и ОГРН.
     */
    public function clearCache()
    {
        $class = get_class($this);
        if ($this->primaryKey){
            Yii::app()->cache->delete($class.'_'.$this->primaryKey);
        }
        Yii::app()->cache->delete($class.self::PREFIX_CACHE_ID_LIST_FULL_DATA);
        Yii::app()->cache->delete($class.self::PREFIX_CACHE_ID_LIST_NAMES);
        Yii::app()->cache->delete($class.self::PREFIX_CACHE_ID_LIST_OGRN);
        Yii::app()->cache->delete($class.self::PREFIX_CACHE_ID_LIST_INN);
    }

    /**
     * @author Evgeniy Chernishev <EvgeniyRRU@gmail.com>
     * Метод выполняет проверку 13-значного ОГРН или 15-значного ОГРНИП
     * стандартному алгоритму
     * @param $value - значение 13-значного ОГРН или 15-значного ОГРНИП
     * @return string $msg - в случае успеха ничего не возвращает
     * в случае ошибки возвращает сообщение об ошибке
     */
    protected function isValidOgrn($value)
    {
        if (!ctype_digit($value)){
            return Yii::t('validator', 'Ошибка. ОГРН должен состоять только из цифр.');
        }

        if(strlen($value) == 13) {
            $check        = substr($value, 0, 12); // просто написать % для определения остатка тут не получилось
            $checkValue1  = $check / 11; // видать php на больших числах считает остаток не точно.
            $checkValue   = $check - (floor($checkValue1)) * 11;
            $controlValue = substr($value, 12);
        } elseif(strlen($value) == 15) {
            $check        = substr($value, 0, 14);
            $checkValue1  = $check / 11;
            $checkValue   = $check - (floor($checkValue1)) * 11;
            $controlValue = substr($value, 14);
        } else {
            return Yii::t('validator', 'Ошибка. ОГРН должен содержать 13 или 15 символов');
        }

        if($checkValue == 10) {
            $checkValue = 0;
        }
        if($checkValue == $controlValue) {
            return '';
        }
        return $msg = Yii::t('validate', "Ошибка. Неверный ОГРН.");
    }

    /**
     * Функция проверяет правильность ИНН.
     * @param string $inn
     * @return bool
     */
    protected function isValidInn($inn)
    {
        if ( preg_match('/\D/', $inn) )
            return false;

        $inn = (string) $inn;
        $len = strlen($inn);

        if ($len === 10){
            return $inn[9] === (string) (((
                        2*$inn[0] + 4*$inn[1] + 10*$inn[2] +
                        3*$inn[3] + 5*$inn[4] +  9*$inn[5] +
                        4*$inn[6] + 6*$inn[7] +  8*$inn[8]
                    ) % 11) % 10);
        } elseif ( $len === 12 ) {
            $num10 = (string) (((
                        7*$inn[0] + 2*$inn[1] + 4*$inn[2] +
                        10*$inn[3] + 3*$inn[4] + 5*$inn[5] +
                        9*$inn[6] + 4*$inn[7] + 6*$inn[8] +
                        8*$inn[9]
                    ) % 11) % 10);

            $num11 = (string) (((
                        3*$inn[0] +  7*$inn[1] + 2*$inn[2] +
                        4*$inn[3] + 10*$inn[4] + 3*$inn[5] +
                        5*$inn[6] +  9*$inn[7] + 4*$inn[8] +
                        6*$inn[9] +  8*$inn[10]
                    ) % 11) % 10);

            return $inn[11] === $num11 && $inn[10] === $num10;
        }
        return false;
    }
}