<?php
/**
 * Скачивание документов.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
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