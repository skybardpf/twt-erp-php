<?php
/**
 * Загрузить файл по его $id.
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class Download_archiveAction extends CAction
{
    /**
     *  Скачать архив с файлами для определенного файла.
     *
     *  @param  integer $id
     *  @param  string $type
     *
     *  @throws CHttpException
     */
    public function run($id, $type)
    {
        /**
         * @var $controller My_eventsController
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Загрузка ахива с файлами';

        $uf = new UploadFile();
        $uf->download_archive(UploadFile::CLIENT_ID, get_class(Event::model()), $id, UploadFile::TYPE_FILE_FILES);

    }
}