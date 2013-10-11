<?php
/**
 * Шаблон контракта.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $name
 * @property string $path
 */
class ContractTemplate extends SOAPModel
{
    const CACHE_PREFIX_LIST_NAMES = '_list_names';

    /**
     * @static
     * @param string $className
     * @return ContractTemplate
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Список шаблонов контракта.
     * @return ContractTemplate[]
     */
    protected function findAll()
    {
        $ret = $this->SOAP->listTemplates($this->where);
        $ret = SoapComponent::parseReturn($ret);
        return $this->publish_list($ret, __CLASS__);
    }

    /**
     * Получить ссылку на скачивание шаблона договора.
     *
     * @param string $contractId
     * @param string $contractTemplateId
     * @param bool $forceCached
     * @return ContractTemplate
     * @throws CHttpException
     */
    public function findByPk($contractId, $contractTemplateId, $forceCached = false)
    {
        $cache_id = __CLASS__ . self::PREFIX_CACHE_MODEL_PK . $contractId . '_' . $contractTemplateId;
        if ($forceCached || ($model = Yii::app()->cache->get($cache_id)) === false) {
            $ret = $this->SOAP->getTemplate(
                array(
                    'id_template' => $contractTemplateId,
                    'id_contract' => $contractId,
                )
            );
            $ret = SoapComponent::parseReturn($ret);
            $model = $this->publish_elem(current($ret), __CLASS__);
            if ($model === null)
                throw new CHttpException(404, 'Не найден шаблон договора');
            Yii::app()->cache->set($cache_id, $model);
        }
        return $model;
    }

    /**
     * @return array list of attribute names.
     */
    public function attributeNames()
    {
        return array(
            'id',
            'name',
            'path'
        );
    }

    /**
     * Список наименований шаблонов контракта. Формат [key => name].
     * Результат сохраняется в кеш.
     * @param string $contractId
     * @return array
     */
    public function listNames($contractId/*, $forceCached = false*/)
    {
//        $cache_id = __CLASS__ . self::CACHE_PREFIX_LIST_NAMES;
//        if ($forceCached || ($data = Yii::app()->cache->get($cache_id)) === false) {
            $elements = self::model()->where('id_contract', $contractId)->findAll();
            $data = array();
            foreach ($elements as $elem) {
                $data[$elem->primaryKey] = $elem->name;
            }
//            Yii::app()->cache->set($cache_id, $data);
//        }
        return $data;
    }
}