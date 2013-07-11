<?php
/**
 * Управление моими событиями (мероприятиями).
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class My_eventsController extends Controller{
    public $layout = 'inner';
    public $menu_current = 'my_events';

    /**
     *  Вывод списка событий (мероприятий).
     */
    public function actionIndex()
    {
        $models = Event::model()->where('deleted', false)->findAll();
        $this->render(
            'index',
            array(
                'models' => $models
            )
        );
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

    /**
     * Просмотр мероприятия.
     *
     * @param int $id
     *
     * @throws CHttpException
     */
    public function actionView($id)
    {
        $model = Event::model()->findByPk($id);
        if (!$model) {
            throw new CHttpException(404, 'Не найдено событие.');
        }

        $this->render(
            'show',
            array(
                'model' => $model,
            )
        );
    }

    /**
     *  Добавление нового события.
     *
     *  @throws CHttpException
     */
    public function actionAdd()
    {
        $model = new Event();

        if ($_POST && !empty($_POST['Event'])) {
            $model->setAttributes($_POST['Event']);

            $model->upload_files = CUploadedFile::getInstancesByName('upload_files');
            $model->list_yur = $model->getStructureOrg();

            if ($model->validate()) {
                try {
                    $model->save();
                    $this->redirect($this->createUrl('index'));
                } catch (Exception $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        }

        $this->render(
            'form',
            array(
                'model' => $model,
            )
        );
    }

    /**
     *  Редактирование события с идентификатором $id.
     *
     *  @param  string $id
     *
     *  @throws CHttpException
     */
    public function actionEdit($id)
    {
        $model = Event::model()->findByPk($id);
        if (!$model) {
            throw new CHttpException(404, 'Не найдено событие.');
        }
        if (!$model->made_by_user){
            throw new CHttpException(500, 'Нельзя редактировать событие, созданное администратором.');
        }

        if ($_POST && !empty($_POST['Event'])) {
            $model->setAttributes($_POST['Event']);

            $model->upload_files  = CUploadedFile::getInstancesByName('upload_files');
            $model->list_yur = $model->getStructureOrg();

            if ($model->validate()) {

                try {
                    $model->save();
                    $this->redirect($this->createUrl('view', array('id' => $model->primaryKey)));
                } catch (Exception $e) {
                    $model->addError('id', $e->getMessage());
                }
            }
        } else {
            $list = array();
            if (isset($model->list_yur[0]) && is_array($model->list_yur[0])){
                for ($i = 0, $l=count($model->list_yur[0])/2; $i<$l; $i++){
                    $type = 'type_yur'.$i;
                    $id = 'id_yur'.$i;
                    if ($model->list_yur[0][$type] == 'Организации'){
                        $list[] = array(
                            'id_yur' => $model->list_yur[0][$id],
                            'type_yur' => 'Организации'
                        );
                    } elseif($model->list_yur[0][$type] == 'Контрагенты'){
                        $list[] = array(
                            'id_yur' => $model->list_yur[0][$id],
                            'type_yur' => 'Контрагенты'
                        );
                    }
                }
            }
            $model->list_yur = $list;

            $organizations = array();
            $contractors = array();
            foreach ($model->list_yur as $v){
                if ($v['type_yur'] == 'Организации'){
                    $organizations[] = $v['id_yur'];
                } elseif ($v['type_yur'] == 'Контрагенты'){
                    $contractors[] = $v['id_yur'];
                }
            }

            $model->json_organizations = CJSON::encode($organizations);
            $model->json_contractors = CJSON::encode($contractors);
        }

        $this->render(
            'form',
            array(
                'model' => $model,
            )
        );
    }

    /**
     *  Удаление события с идентификатором $id.
     *
     *  @param  string $id
     *
     *  @throws CHttpException
     */
    public function actionDelete($id)
    {
        $model = Event::model()->findByPk($id);
        if (!$model) {
            throw new CHttpException(404, 'Не найдено событие.');
        }

        if (Yii::app()->request->isAjaxRequest) {
            $ret = array();
            try {
                if (!$model->made_by_user){
                    throw new CException('Нельзя удалить событие, созданное администратором.');
                }
                $model->delete();
            } catch (Exception $e) {
                $ret['error'] = $e->getMessage();
            }
            echo CJSON::encode($ret);
            Yii::app()->end();
        } else {
            if (!$model->made_by_user){
                throw new CHttpException(500, 'Нельзя удалить событие, созданное администратором.');
            }
            if (isset($_POST['result'])) {
                switch ($_POST['result']) {
                    case 'yes':
                        if ($model->delete()) {
                            $this->redirect($this->createUrl('index'));
                        } else {
                            throw new CHttpException(500, 'Не удалось удалить довереность.');
                        }
                    break;
                    default:
                        $this->redirect($this->createUrl('index'));
                    break;
                }
            }
//            $this->render('documents/power_attorney_le/delete', array('model' => $model));
        }
    }

    /**
     *  Удалить файл по его $id.
     *
     *  @param  int $id
     *
     *  @throws CHttpException
     */
    public function actionDelete_file($id)
    {
        try {
            $uf = new UploadFile();
            $uf->delete($id);

            if (Yii::app()->request->isAjaxRequest) {
                echo CJSON::encode(
                    array(
                        'success' => true,
                    )
                );
                Yii::app()->end();
            } else {
                echo 'Файл успешно удален.';
            }

        } catch(UploadFileException $e) {
            if (Yii::app()->request->isAjaxRequest) {
                echo CJSON::encode(
                    array(
                        'success' => false,
                        'message' => $e->getMessage()
                    )
                );
                Yii::app()->end();
            } else {
                throw new CHttpException(500, $e->getMessage());
            }
        }
    }

    /**
     *  Скачать архив с файлами для определенного файла.
     *
     *  @param  string $id
     *  @param  string $type
     *
     *  @throws CHttpException
     */
    public function actionDownload_archive($id, $type)
    {
        $uf = new UploadFile();
        $uf->download_archive(UploadFile::CLIENT_ID, get_class(Event::model()), $id, UploadFile::TYPE_FILE_FILES);
    }

    /**
     *  Скачать файл по его $id.
     *
     *  @param  int $id
     *
     *  @throws CHttpException
     */
    public function actionDownload_file($id)
    {
        $uf = new UploadFile();
        $uf->download($id);
    }

    /**
     * Только Ajax. Рендерим форму со списком организаций. Показываются только организации,
     * которые еще не привязанны к данному событию.
     */
    public function actionGet_list_organizations($selected_ids) {
        if (Yii::app()->request->isAjaxRequest) {
            try {
                $selected_ids = CJSON::decode($selected_ids);
                $p = Organizations::getValues();
                foreach ($selected_ids as $pid){
                    if (isset($p[$pid])){
                        unset($p[$pid]);
                    }
                }
                $p = array_merge(array('' => 'Выберите'), $p);

                $this->renderPartial(
                    '/my_events/get_list_organizations',
                    array(
                        'data' => $p,
                    ),
                    false
                );

            } catch (CException $e){
                echo $e->getMessage();
            }
        }
    }
}