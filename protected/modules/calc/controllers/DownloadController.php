<?php
/**
 * Отдаем на скачивание pdf файлы с заявками.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class DownloadController extends Controller
{
    public $layout = 'calc';

    /**
     * @return array
     */
    public function actions()
    {
        return array(
            'download' => 'application.modules.calc.controllers.Download.DownloadAction',
        );
    }
}