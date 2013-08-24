<?php
/**
 * Загрузить файл по его $id.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class Download_fileAction extends CAction
{
    /**
     *  Загрузить файл по его $id.
     *  @param  integer $id
     *  @throws UploadFileException | CHttpException
     */
    public function run($id)
    {
        /**
         * @var $controller My_eventsController
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Загрузка файла';

        $uf = new UploadFile();
        $uf->download($id);
    }
}