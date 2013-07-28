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
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            'get_countries' => 'application.modules.legal.controllers.My_events.GetCountriesAction',
            '_html_form_select_element' => 'application.modules.legal.controllers.My_events.HtmlFormSelectElementAction',
            '_html_row_element' => 'application.modules.legal.controllers.My_events.HtmlRowElementAction',

            'edit' => 'application.modules.legal.controllers.My_events.UpdateAction'
        );
    }

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
            'view',
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
                            throw new CHttpException(500, 'Не удалось удалить событие.');
                        }
                    break;
                    default:
                        $this->redirect($this->createUrl('index'));
                    break;
                }
            }
            $this->render('/my_events/delete', array(
                'model' => $model
            ));
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
}