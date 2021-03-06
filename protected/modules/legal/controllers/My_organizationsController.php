<?php
/**
 * User: Forgon
 * Date: 23.04.13
 */
class My_organizationsController extends Controller {

	public $layout = 'inner';
	/** @var string Пункт левого меню */
	public $menu_current = 'legal';
	/** @var string Вкладка верхнего меню одной организации */
	public $cur_tab = '';
	/** @var Organizations Текущая просматриваемая организация */
	public $organization = NULL;

	/**
	 * Список моих организаций
	 */
	public function actionIndex()
    {
		$models = Organizations::model()->where('deleted', false)->findAll();
		$this->render('list', array('models' => $models));
	}

	/**
	 * Просмотр организации
	 * @param $id
	 *
	 * @throws CHttpException
	 */
	public function actionView($id)
    {
		/** @var $model Organizations */
		$model = Organizations::model()->findByPk($id);
		if (!$model) {
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }
//		$this->organization = $model;

        $this->render('/my_organizations/show', array(
            'content' => $this->renderPartial('/my_organizations/tab_info',
                array(
                    'organization' => $model
                ), true),
            'organization' => $model,
            'cur_tab' => 'info',
        ));
	}

	public function actionDelete($id)
    {
		$ret = array();
		if (!Organizations::model()->delete_by_id($id)) {
			$ret['error'] = 'Организацию удалить невозможно';
		}
		echo CJSON::encode($ret);
		if (Yii::app()->request->isAjaxRequest) {
			Yii::app()->end();
		} elseif (YII_DEBUG) {
			CVarDumper::dump($ret,5,1);
			$this->renderText('Debug_out');
		} else {
			throw new CHttpException(404);
		}
	}

    /**
     *  Добавление новой организации.
     *
     *  @throws CHttpException
     */
    public function actionAdd()
    {
        /** @var $org Organizations */
        $org = new Organizations();

        $error = '';
        if ($_POST && !empty($_POST['Organizations'])) {
            $org->setAttributes($_POST['Organizations']);
            if ($org->validate()) {
                try {
                    $org->save();
//                    $this->redirect($this->createUrl('index'));
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }

        $this->render('/my_organizations/show', array(
            'content' => $this->renderPartial('/my_organizations/form',
                array(
                    'model' => $org,
                    'error' => $error,
                ), true),
            'organization' => $org,
            'cur_tab' => 'info',
        ));
    }

    /**
     *  Редактирование организации.
     *
     *  @param  string $id
     *  @throws CHttpException
     */
    public function actionEdit($id)
    {
        /** @var $org Organizations */
        $org = Organizations::model()->findByPk($id);
        if (!$org) {
            throw new CHttpException(404, 'Не найдено юр. лицо.');
        }

        $error = '';
        if ($_POST && !empty($_POST['Organizations'])) {
            $org->setAttributes($_POST['Organizations']);
            if ($org->validate()) {
                try {
                    $org->save();
                    $this->redirect($this->createUrl('view', array('id' => $id)));
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }

        $this->render('/my_organizations/show', array(
            'content' => $this->renderPartial('/my_organizations/form',
                array(
                    'model' => $org,
                    'error' => $error,
                ), true),
            'organization' => $org,
            'cur_tab' => 'info',
        ));
    }
}