<?php
/**
 *  Календарь событий.
 *
 *  User: Skibardin A.A.
 *  Date: 03.07.13
 */
class My_eventsController extends Controller{
    public $layout = 'inner';
    public $menu_current = 'legal';

    /**
     *  Выводим календарь событий для юридического лица $org_id.
     *
     *  @param string $org_id
     */
    public function actionIndex($org_id)
    {
        $this->redirect($this->createUrl('list', array('org_id' => $org_id)));
    }

    /**
     *  Выводим календарь событий для юридического лица $org_id.
     *
     *  @param string $org_id
     *
     *  @throws CHttpException
     */
    public function actionList($org_id)
    {
        $org = Organizations::model()->findByPk($org_id);
        if (!$org) {
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }

        $this->render('/my_organizations/show', array(
            'content' => $this->renderPartial('/my_events/list',
                array(
                    'organization' => $org
                ), true),
            'organization' => $org,
            'cur_tab' => 'my_events',
        ));
    }

    public function actionMy_events($id) {
        $this->menu_current = 'index';
        $this->cur_tab = 'my_events';
        $model = Organizations::model()->findByPk($id);
        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/my_events/list', array('id' => $id), true), 'model' => $model));
    }
}