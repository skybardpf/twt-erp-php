<?php
/**
 * Скачивание документов.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class DownloadController extends Controller {
	public function actions()
    {
        return array(
            'download' => 'application.controllers.Download.DownloadAction',
            'delete' => 'application.controllers.Download.DeleteAction'
        );
    }
}