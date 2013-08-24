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
            'download' => 'application.modules.legal.controllers.Download.DownloadAction',
            'delete' => 'application.modules.legal.controllers.Download.DeleteAction'
        );
    }
}