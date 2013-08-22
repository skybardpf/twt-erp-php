<?php
/**
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class DownloadAction extends CAction
{
    public function run($path)
    {
//        $encoded = strtr(base64_encode(addslashes(gzcompress(serialize($string),9))), '+/=', '-_,');
        $path = unserialize(gzuncompress(stripslashes(base64_decode(strtr($path, '-_,', '+/=')))));
        $path = Yii::getPathOfAlias('filestorage').DIRECTORY_SEPARATOR.$path;
        if (!file_exists($path)){
            echo 'Файл не существует';
            die;
        }

        $filename = time().'.pdf';
        header('Set-Cookie: fileDownload=true; path=/');
        header('Cache-Control: max-age=60, must-revalidate');
        header('Content-type: application/*');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
    }
}