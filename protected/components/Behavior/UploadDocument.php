<?php
/**
 * Class UploadDocumentException
 */
class UploadDocumentException extends CException{}

/**
 * Поведение, реализующее загрузку файлов для различных моделей документов.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
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
        if (!file_exists($this->uploadDir)){
            if (!mkdir($this->uploadDir, 0777)){
                throw new CHttpException(500, 'Не удалось создать директорию для загрузки документов.');
            }
        }
        if (!is_dir($this->uploadDir) || !is_writable($this->uploadDir)){
            throw new CHttpException(500, 'Не доступна директория для загрузки документов.');
        }
        parent::afterConstruct($event);
    }

    /**
     *  Загрузить указанный документ.
     *
     *  @param string $path
     *  @param CUploadedFile $file
     *  @return bool
     *
     *  @throws UploadDocumentException
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
}

/**
 * Class MDocumentCategory
 * Категория документа.
 */
class MDocumentCategory extends CEnumerable{
    const SCAN = 'scan';
    const FILE = 'file';
}