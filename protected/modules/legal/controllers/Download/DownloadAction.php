<?php
/**
 * Скачивание документа.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
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
            header('Cache-Control: max-age=60, must-revalidate');
            header('Content-type: application/*');
            header('Content-Disposition: attachment; filename="'.$file.'"');
            header('Content-Length: ' . filesize($filename));
            readfile($filename);
        }
    }
}