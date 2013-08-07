<?php
/**
 * Управление группами контрагентов.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class Contractor_groupController extends Controller{
    public $layout = 'inner';
    public $menu_current = 'contractors';
    public $pageTitle = 'TWT Consult | Контрагенты | Группы контрагентов';

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            'index' => 'application.modules.legal.controllers.ContractorGroup.IndexAction',
            'update' => 'application.modules.legal.controllers.ContractorGroup.UpdateAction',
            'create' => 'application.modules.legal.controllers.ContractorGroup.CreateAction',
            'delete' => 'application.modules.legal.controllers.ContractorGroup.DeleteAction',
        );
    }

    /**
     * @param string $id    Идентификатор группы.
     * @return ContractorGroup   Получаем модель.
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $cache_id = get_class(ContractorGroup::model()).'_'.$id;
        $model = Yii::app()->cache->get($cache_id);
        if ($model === false){
            $model = ContractorGroup::model()->findByPk($id);
            if ($model === null) {
                throw new CHttpException(404, 'Не найдена группа контрагента.');
            }
            Yii::app()->cache->set($cache_id, $model);
        }
        return $model;
    }

    /**
     * @param ContractorGroup $parent
     * @return ContractorGroup Возвращаем созданную модель.
     */
    public function createModel(ContractorGroup $parent)
    {
        $model = new ContractorGroup();
        $model->parent_id = $parent->primaryKey;
        return $model;
    }
}