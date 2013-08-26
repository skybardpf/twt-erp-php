<?php
/**
 * Class UploadDocumentException
 */
class UploadDocumentException extends CException{}

/**
 * Поведение, реализующее загрузку файлов для различных моделей документов.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class UploadDocument extends CModelBehavior
{
    public $uploadDir = '';

    /**
     * @param CEvent $event event parameter
     * @throws CHttpException
     */
    public function afterConstruct($event)
    {
//        var_dump($this->uploadDir);
//        var_dump(file_exists($this->uploadDir));
//        die;
        if (!file_exists($this->uploadDir)){
            if (!mkdir($this->uploadDir, 0777, true)){
                throw new CHttpException(500, 'Не удалось создать директорию для загрузки документов.');
            }
        }
        if (!is_dir($this->uploadDir) || !is_writable($this->uploadDir)){
            throw new CHttpException(500, 'Не доступна директория для загрузки документов.');
        }
        parent::afterConstruct($event);
    }

    /**
     * Загрузить указанный документ.
     *
     * @param string $path
     * @param CUploadedFile $file
     * @return bool
     *
     * @throws UploadDocumentException
     */
    public function upload($path, CUploadedFile $file)
    {
        try {
            $upload_dir = $this->uploadDir . DIRECTORY_SEPARATOR . $path;

            if (is_dir($upload_dir)){
                if (!is_writable($upload_dir)){
                    throw new UploadDocumentException('Не доступна директория для загрузки документов.');
                }
            } else {
                if (!mkdir($upload_dir, 0777, true)){
                    throw new UploadDocumentException('Не удалось создать директорию для загрузки документов.');
                }
            }
            if (!$file->saveAs($upload_dir. DIRECTORY_SEPARATOR . $file->name)) {
                throw new UploadDocumentException('Не удалось загрузить файл.');
            }
        } catch (UploadDocumentException $e){
            Yii::log($e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Удаляет указанные в $files документы по пути $path.
     *
     * @param string $path
     * @param array $files
     * @throws UploadDocumentException
     */
    public function removeFiles($path, $files)
    {
        $dir = $this->uploadDir . DIRECTORY_SEPARATOR . $path;
        if (!is_dir($dir) || (!is_writable($dir))){
            throw new UploadDocumentException('Директория не существует.');
        }
        foreach($files as $file){
            if (file_exists($dir . DIRECTORY_SEPARATOR . $file)){
                unlink($dir . DIRECTORY_SEPARATOR . $file);
            }
        }
    }

    /**
     * Перемещаем указанные в $files документы, находящиеся по пути $path в
     * папку приемник $destination.
     *
     * @param string $source
     * @param string $destination
     * @param array $files
     * @throws UploadDocumentException
     */
    public function moveFiles($source, $destination, $files)
    {
        $source = $this->uploadDir . DIRECTORY_SEPARATOR . $source;
        if (!is_dir($source) || (!is_readable($source))){
            throw new UploadDocumentException('Директория-источник не существует или не доступна для чтения.');
        }
        $destination = $this->uploadDir . DIRECTORY_SEPARATOR . $destination;
        if (is_dir($destination)){
            if (!is_writable($destination)){
                throw new UploadDocumentException('Директория-приемник не доступна для записи.');
            }
        } elseif (!mkdir($destination, 0777, true)) {
            throw new UploadDocumentException('Не удалось создать директория-приемник.');
        }

        foreach($files as $file){
            if (file_exists($source . DIRECTORY_SEPARATOR . $file)){
                rename($source.DIRECTORY_SEPARATOR.$file, $destination. DIRECTORY_SEPARATOR.$file);
            }
        }
    }
}