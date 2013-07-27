<?php
/**
 * Управление событиями (мероприятиями) для кокретной организации.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class Calendar_eventsController extends Controller{
    public $layout = 'inner';
    public $menu_current = 'legal';
    public $current_tab = 'calendar_events';
    public $pageTitle = 'TWT Consult | Мои организации';

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            'list' => 'application.modules.legal.controllers.Calendar_events.ListAction',
            'view' => 'application.modules.legal.controllers.Calendar_events.ViewAction',
            'edit' => 'application.modules.legal.controllers.Calendar_events.UpdateAction',
            'delete' => 'application.modules.legal.controllers.Calendar_events.DeleteAction',
        );
    }

    /**
     * Получаем список событий указанной организации.
     * @param Organizations $org
     * @return Event[]
     */
    public function getDataProvider(Organizations $org)
    {
        $cache_id = get_class(Event::model()).'_list_org_id_'.$org->primaryKey;
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false){
            $data = Event::model()
                ->where('id_yur', $org->primaryKey)
                ->where('deleted', false)
                ->findAll();

            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * @param string $id Идентификатор события.
     * @return Event
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $cache_id = get_class(Event::model()).'_'.$id;
        $model = Yii::app()->cache->get($cache_id);
        if ($model === false){
            $model = Event::model()->findByPk($id);
            if ($model === null) {
                throw new CHttpException(404, 'Не найдено событие.');
            }
            Yii::app()->cache->set($cache_id, $model, 0);
        }
        return $model;
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
            $uf->delete_file($id);

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