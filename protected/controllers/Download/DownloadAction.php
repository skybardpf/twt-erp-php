<?php
/**
 * Скачивание документа.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class DownloadAction extends CAction
{
    /**
     * Скачивание документа.
     */
    public function run($class_name, $id, $type, $file)
    {
        $path = Yii::getPathOfAlias(Yii::app()->params->uploadDocumentDir)
            . DIRECTORY_SEPARATOR . Yii::app()->user->getId()
            . DIRECTORY_SEPARATOR . $class_name
            . DIRECTORY_SEPARATOR . $id
            . DIRECTORY_SEPARATOR . $type;

        $filename = $path . DIRECTORY_SEPARATOR . $file;
        if (!file_exists($filename)){
            echo 'Файл не найден';
        } else {
            header('Set-Cookie: fileDownload=true; path=/');
            Yii::app()->request->sendFile($file, file_get_contents($filename));
        }
    }
}