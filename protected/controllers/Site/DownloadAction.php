<?php
/**
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class DownloadAction extends CAction
{
    public function run($path)
    {
//        $path = strtr(base64_encode(addslashes(gzcompress(serialize('Прототипы.Организации.Календарь событий.Общий.pdf'),9))), '+/=', '-_,');
        $this->controller->disableProfilers();

        $path = unserialize(gzuncompress(stripslashes(base64_decode(strtr($path, '-_,', '+/=')))));
        $path = str_replace('\\', '/', $path);
        $path = Yii::getPathOfAlias('filestorage').DIRECTORY_SEPARATOR.$path;
        if (!file_exists($path)){
            echo 'NotFound';
            die;
        }
        Yii::app()->request->sendFile(time().'.pdf', file_get_contents($path));
    }
}