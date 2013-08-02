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

            '_json_contractor_groups' => 'application.modules.legal.controllers.ContractorGroup.JsonContractorGroupsAction',

//            'view' => 'application.modules.legal.controllers.ContractorGroup.ViewAction',
//            'edit' => 'application.modules.legal.controllers.ContractorGroup.UpdateAction',
//            'add' => 'application.modules.legal.controllers.ContractorGroup.CreateAction',
//            'delete' => 'application.modules.legal.controllers.ContractorGroup.DeleteAction',
        );
    }

//    /**
//     * @param string $id    Идентификатор контрагента.
//     * @return ContractorGroup   Получаем модель Контрагент.
//     * @throws CHttpException
//     */
//    public function loadModel($id)
//    {
//        $cache_id = get_class(ContractorGroup::model()).'_'.$id;
//        $model = Yii::app()->cache->get($cache_id);
//        if ($model === false){
//            $model = Contractor::model()->findByPk($id);
//            if ($model === null) {
//                throw new CHttpException(404, 'Не найден контрагент.');
//            }
//            Yii::app()->cache->set($cache_id, $model);
//        }
//        return $model;
//    }

//    /**
//     * @return ContractorGroup Возвращаем созданную модель.
//     */
//    public function createModel()
//    {
//        $model = new ContractorGroup();
//        return $model;
//    }
}