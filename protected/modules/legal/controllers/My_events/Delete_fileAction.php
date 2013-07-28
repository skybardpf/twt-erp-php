<?php
/**
 * Удалить файл по его $id.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class Delete_fileAction extends CAction
{
    /**
     *  Удалить файл по его $id.
     *  @param  integer $id
     *  @throws UploadFileException | CHttpException
     */
    public function run($id)
    {
        /**
         * @var $controller My_eventsController
         */
        $controller = $this->controller;

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
                $controller->pageTitle .= ' | Удаление файла';
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
}