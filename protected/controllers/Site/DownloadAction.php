<?php
/**
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class DownloadAction extends CAction
{
    public function run($path)
    {
        if (empty($path)){
            echo 'Bad path';
            Yii::app()->end(400);
        }
        $this->controller->disableProfile();

        $path = unserialize(gzuncompress(stripslashes(base64_decode(strtr($path, '-_,', '+/=')))));
        if (empty($path)){
            echo 'Empty path';
            Yii::app()->end(400);
        }
        $path = str_replace('\\', '/', $path);
        $path = Yii::getPathOfAlias('filestorage').DIRECTORY_SEPARATOR.$path;
        if (!file_exists($path)){
            echo 'Not found';
            Yii::app()->end(404);
        }
        header('Set-Cookie: fileDownload=true; path=/');
        Yii::app()->request->sendFile(basename($path), file_get_contents($path));
    }
}