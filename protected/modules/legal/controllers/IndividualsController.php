<?php
/**
 * Физические лица.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @see Individuals
 */
class IndividualsController extends Controller
{
    public $layout       = 'inner';
    public $menu_current = 'individuals';
	public $cur_tab      = '';

    public $pageTitle = 'TWT Consult | Мои физические лица';

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            'index' => 'application.modules.legal.controllers.Individuals.IndexAction',
            'add' => 'application.modules.legal.controllers.Individuals.CreateAction',
            'view' => 'application.modules.legal.controllers.Individuals.ViewAction',
            'edit' => 'application.modules.legal.controllers.Individuals.UpdateAction',
            'delete' => 'application.modules.legal.controllers.Individuals.DeleteAction',

            'cart' => 'application.modules.legal.controllers.Individuals.CartAction',
        );
    }

    /**
     * Список физичекских лиц.
     * @return Individuals[]
     */
    public function getDataProvider()
    {
        return Individuals::getFullValues();
    }

    /**
     * @param string $id Идентификатор физичекского лица.
     * @return Individuals
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $cache_id = get_class(Individuals::model()).'_'.$id;
        $model = Yii::app()->cache->get($cache_id);
        if ($model === false){
            $model = Individuals::model()->findByPk($id);
            if ($model === null) {
                throw new CHttpException(404, 'Не найдено физическое лицо.');
            }
            Yii::app()->cache->set($cache_id, $model, 0);
        }
        return $model;
    }

    /**
     * Создаем новое физичекское лицо.
     * @return Individuals
     */
    public function createModel()
    {
        $model = new Individuals();
        return $model;
    }
}
