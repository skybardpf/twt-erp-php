<?php
/**
 * Class UploadFileException
 */
class UploadFileException extends CException{}

/**
 *  Модель для работы с файла, сканами.
 *  Загрузка на сервер, закачка с сервера, удаление.
 *
 *  User: Skibardin A.A.
 *  Date: 04.07.13
 *
 *  @property string    $filename
 *  @property int       $load_date  - timestamp
 *  @property string    $type       - files or scans
 *  @property int       $client_id
 *  @property string    $model_name
 *  @property string    $model_id
 */
class UploadFile extends CActiveRecord {
    const CLIENT_ID = 1;

    const TYPE_FILE_FILES = 'files';
    const TYPE_FILE_SCANS = 'scans';

    const DIR_UPLOADS = '/../uploads';
    const DIR_TMP = '/tmp';

    private $_is_uploaded   = false;
    private $_filename      = NULL;
    private $_load_date     = NULL;

    public $type;
    public $client_id;
    public $model_name;
    public $model_id;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'upload_file';
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UploadFile the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function attributeNames()
    {
        return array(
//            'filename',
//            'load_date',
            'type',
            'client_id',
            'model_name',
            'model_id',
        );
    }

    public function rules()
    {
        return array(
            array('type', 'required'),
            array('type', 'in', 'range' => array(self::TYPE_FILE_FILES, self::TYPE_FILE_SCANS)),

            array('client_id', 'required'),
            array('model_name', 'required'),
            array('model_id', 'required'),
        );
    }

    /**
     *  Файл успешно загружен.
     *
     *  @return boolean
     */
    public function isUploaded()
    {
        return $this->_is_uploaded;
    }

    /**
     *  Получить название загруженного файла.
     *
     *  @return string | NULL
     */
    public function getFileName()
    {
        return ($this->_is_uploaded) ? $this->_filename : NULL;
    }

    /**
     *  Получить время загрузки.
     *
     *  @return int | NULL
     */
    public function getLoadDate()
    {
        return ($this->_is_uploaded) ? $this->_load_date : NULL;
    }

    /**
     *  Получить список файлов.
     *
     *  @static
     *
     *  @param int      $client_id
     *  @param string   $model_name
     *  @param string   $model_id
     *  @param string   $type
     *
     *  @return array
     *
     *  @throws UploadFileException
     */
    public static function getListFiles($client_id, $model_name, $model_id, $type = NULL)
    {
        $cmd = Yii::app()->db->createCommand(
            'SELECT id, type, filename
            FROM '.UploadFile::model()->tableName().'
            WHERE client_id=:client_id AND model_name=:model_name AND model_id=:model_id'
        );
        $files = $cmd->queryAll(true, array(
            ':client_id'    => $client_id,
            ':model_name'   => $model_name,
            ':model_id'     => $model_id,
        ));
        return $files;
    }

    /**
     *  Получить кол-во файлов различных типов.
     *
     *  @static
     *
     *  @param int      $client_id
     *  @param string   $model_name
     *  @param string   $model_id
     *
     *  @return array
     *
     *  @throws UploadFileException
     */
    public static function getCountTypeFiles($client_id, $model_name, $model_id)
    {
        $cmd = Yii::app()->db->createCommand(
            'SELECT count(*) as `count`, type
            FROM '.UploadFile::model()->tableName().'
            WHERE client_id=:client_id AND model_name=:model_name AND model_id=:model_id
            GROUP BY type'
        );
        $files = $cmd->queryAll(true, array(
            ':client_id'    => $client_id,
            ':model_name'   => $model_name,
            ':model_id'     => $model_id,
        ));
        $res = array();
        foreach($files as $f){
            $res[$f['type']] = $f['count'];
        }
        return $res;
    }

    /**
     *  Загрузить указанный документ. Возвращает ID созданной записи.
     *
     *  @param CUploadedFile   $file
     *  @param int      $client_id
     *  @param string   $model_name
     *  @param string   $model_id
     *  @param string   $type
     *
     *  @return int
     *
     *  @throws UploadFileException
     */
    public function upload(CUploadedFile $file, $client_id, $model_name, $model_id, $type)
    {
        try {
            $this->client_id    = $client_id;
            $this->model_name   = $model_name;
            $this->model_id     = $model_id;
            $this->type         = $type;

            if (!$this->validate()){
                throw new UploadFileException('Заполнены не все атрибуты.');
            }
            $upload_dir = $this->getDirUpload() . DIRECTORY_SEPARATOR . $this->getDirUploadModel();
            if (!is_dir($upload_dir) || !is_writable($upload_dir)){
                throw new UploadFileException('Не доступна директория для загрузки файлов.');
            }
            $time = time();
            if (!$file->saveAs($upload_dir. DIRECTORY_SEPARATOR . $file->name)) {
                throw new UploadFileException('Не удалось загрузить файл.');
            }

            $this->_filename    = $file->name;
            $this->_load_date   = $time ;

            $cmd = Yii::app()->db->createCommand(
                'INSERT INTO '.UploadFile::model()->tableName().'
                    (client_id, model_name, model_id, type, filename, load_date, size)
                VALUES (:client_id, :model_name, :model_id, :type, :filename, :load_date, :size)'
            );
            $cmd->execute(array(
                ':client_id'    => $client_id,
                ':model_name'   => $model_name,
                ':model_id'     => $model_id,
                ':type'         => $type,
                ':filename'     => $this->_filename,
                ':load_date'    => $this->_load_date,
                ':size'         => $file->size,
            ));

            $cmd = Yii::app()->db->createCommand(
                'SELECT last_insert_rowid() as `id`
                FROM '.UploadFile::model()->tableName()
            );
            $f = $cmd->queryRow();

            $this->_is_uploaded = true;
            return $f['id'];

        } catch (UploadFileException $e){
            $this->_is_uploaded = false;
            return NULL;
        }
    }

    /**
     *  Отдает на скачку архив с документами.
     *
     *  @param int      $client_id
     *  @param string   $model_name
     *  @param string   $model_id
     *  @param string   $type
     *
     *  @return void
     *
     *  @throws UploadFileException
     */
    public function download_archive($client_id, $model_name, $model_id, $type)
    {
        $this->client_id    = $client_id;
        $this->model_name   = $model_name;
        $this->model_id     = $model_id;
        $this->type         = $type;

        if (!$this->validate()){
            throw new UploadFileException('Заполнены не все атрибуты.');
        }

        $cmd = Yii::app()->db->createCommand(
            'SELECT COUNT(*) as `count`
            FROM '.UploadFile::model()->tableName().'
            WHERE client_id=:client_id AND model_name=:model_name AND model_id=:model_id AND type=:type'
        );
        $files = $cmd->queryRow(true, array(
            ':client_id'    => $client_id,
            ':model_name'   => $model_name,
            ':model_id'     => $model_id,
            ':type'         => $type,
        ));
        if (empty($files['count'])){
            throw new UploadFileException('Нет файлов для закачки.');
        }

        $upload_dir = $this->getDirUpload() . DIRECTORY_SEPARATOR . $this->getDirUploadModel(false);
        $out = NULL;
        $ret = NULL;
        $zip_name = $type.'_'.time().'.zip';
        $zip = self::DIR_TMP . DIRECTORY_SEPARATOR . $zip_name;

        exec('cd '.$upload_dir.'; zip -r '.$zip.' ./*', $out, $ret);
        if ($ret !== 0){
            throw new UploadFileException('Не удалось создать архив.');
        }
        header('Content-type: application/zip');
        header('Content-Disposition: attachment; filename="'.$zip_name.'"');
        header('Content-Length: ' . filesize($zip));
        readfile($zip);
        unlink($zip);
    }

    /**
     *  Загрузить указанный в $id документ.
     *
     *  @param int   $file_id
     *  @return boolean
     *
     *  @throws CHttpException
     */
    public function download($file_id)
    {
        try {
            $cmd = Yii::app()->db->createCommand(
                'SELECT filename, client_id, model_name, model_id, type, size
                FROM '.UploadFile::model()->tableName().'
                WHERE id=:id'
            );
            $f = $cmd->queryRow(true, array(
                ':id' => $file_id,
            ));
            if (empty($f)){
                throw new CHttpException(500, 'Не найден файл для закачки.');
            }

            $this->client_id    = $f['client_id'];
            $this->model_name   = $f['model_name'];
            $this->model_id     = $f['model_id'];
            $this->type         = $f['type'];

            if (!$this->validate()){
                throw new UploadFileException('Заполнены не все атрибуты.');
            }
            $upload_dir = $this->getDirUpload() . DIRECTORY_SEPARATOR . $this->getDirUploadModel(false);
            if (!file_exists($upload_dir . DIRECTORY_SEPARATOR . $f['filename'])){
                throw new CHttpException(500, 'Файл не существует.');
            }

            header('Content-type: application/*');
            header('Content-Disposition: attachment; filename="'.$f['filename'].'"');
            header('Content-Length: ' . $f['size']);
            readfile($upload_dir . DIRECTORY_SEPARATOR . $f['filename']);

        } catch (UploadFileException $e){
//            $this->_is_uploaded = false;
            return false;
        }
//        $this->_is_uploaded = true;
        return true;
    }

    /**
     *  Перемещаем документ.
     *
     *  @param  int     $file_id
     *  @param  string  $model_id
     *  @return void
     *
     *  @throws UploadFileException
     */
    public function move($file_id, $model_id)
    {
        $cmd = Yii::app()->db->createCommand(
            'SELECT filename, client_id, model_name, model_id, type
            FROM '.UploadFile::model()->tableName().'
            WHERE id=:id'
        );
        $f = $cmd->queryRow(true, array(
            ':id' => $file_id,
        ));
        if (empty($f)){
            throw new UploadFileException('Не найден файл для перемещения.');
        }

        if ($model_id != $f['model_id']){
            $this->client_id    = $f['client_id'];
            $this->model_name   = $f['model_name'];
            $this->model_id     = $f['model_id'];
            $this->type         = $f['type'];

            if (!$this->validate()){
                throw new UploadFileException('Заполнены не все атрибуты.');
            }
            $upload_dir = $this->getDirUpload() . DIRECTORY_SEPARATOR . $this->getDirUploadModel(false);
            $file_source = $upload_dir . DIRECTORY_SEPARATOR . $f['filename'];
            if (!file_exists($file_source) || !is_writable($file_source)){
                throw new UploadFileException('Нет доступа к файлу.');
            }

            $this->model_id = $model_id;
            $upload_dir = $this->getDirUpload() . DIRECTORY_SEPARATOR . $this->getDirUploadModel();
            $file_destination = $upload_dir . DIRECTORY_SEPARATOR . $f['filename'];

            if (!rename($file_source, $file_destination)){
                throw new UploadFileException('Не удалось переименовать файл.');
            }

            $cmd = Yii::app()->db->createCommand(
                'UPDATE '.UploadFile::model()->tableName().'
                SET model_id=:model_id
                WHERE id=:id'
            );
            $cmd->execute(array(
                ':id' => $file_id,
                ':model_id' => $model_id,
            ));
        }
    }

    /**
     *  Удалить указанный в $id документ.
     *
     *  @param  int   $file_id
     *  @return void
     *
     *  @throws UploadFileException
     */
    public function delete_file($file_id)
    {
        $cmd = Yii::app()->db->createCommand(
            'SELECT filename, client_id, model_name, model_id, type
            FROM '.UploadFile::model()->tableName().'
            WHERE id=:id'
        );
        $f = $cmd->queryRow(true, array(
            ':id' => $file_id,
        ));
        if (empty($f)){
            throw new UploadFileException('Не найден файл для удаления.');
        }

        $this->client_id    = $f['client_id'];
        $this->model_name   = $f['model_name'];
        $this->model_id     = $f['model_id'];
        $this->type         = $f['type'];

        if (!$this->validate()){
            throw new UploadFileException('Заполнены не все атрибуты.');
        }
        $upload_dir = $this->getDirUpload() . DIRECTORY_SEPARATOR . $this->getDirUploadModel(false);
        if (!file_exists($upload_dir . DIRECTORY_SEPARATOR . $f['filename'])){
            throw new UploadFileException('Файл не существует.');
        }

        if (!unlink($upload_dir . DIRECTORY_SEPARATOR . $f['filename'])){
            throw new UploadFileException('Заполнены не все атрибуты.');
        }

        $cmd = Yii::app()->db->createCommand(
            'DELETE FROM '.UploadFile::model()->tableName().' WHERE id=:id'
        );
        $cmd->execute(array(
            ':id' => $file_id,
        ));
    }

    /**
     *  Проверяет существует ли указанный файл и если да, тогда устанавливает флаг,
     *  что файл уже загружен.
     *
     *  @param  string    $filename
     *  @param  string    $load_date
     *  @return boolean
     *
     *  @throws UploadFileException
     */
    public function setFileExists($filename, $load_date)
    {
        $file_path = $this->getDirUpload() .
            DIRECTORY_SEPARATOR . $this->getDirUploadModel() .
            DIRECTORY_SEPARATOR . $filename;
        if (!file_exists($file_path) || !is_file($file_path)){
            $this->_is_uploaded = false;
        } else {
            $this->_filename = $filename;
            $time = strtotime($load_date);
            $this->_load_date = ($time === FALSE) ? time() : $time;
            $this->_is_uploaded = true;
        }
        return $this->_is_uploaded;
    }

    /**
     *  Получить полный путь к директории для определенной сущности.
     *  Если директории не существуют они будут созданы.
     *
     *  @param  boolean $create_dir
     *  @return string
     */
    private function getDirUploadModel($create_dir = true)
    {
//        $type = ($this->type == self::TYPE_FILE_SCANS) ? 'scans' : 'files';
        $dir_upload = $this->getDirUpload();
        $dir = $this->client_id .
            DIRECTORY_SEPARATOR . $this->model_name .
            DIRECTORY_SEPARATOR . $this->model_id .
            DIRECTORY_SEPARATOR . $this->type;
        if(!is_dir($dir_upload . DIRECTORY_SEPARATOR . $dir) && $create_dir){
            mkdir($dir_upload . DIRECTORY_SEPARATOR . $dir, 0755, true);
        }
        return $dir;
    }

    /**
     * Получить корень директории загрузки.
     *
     * @return string
     * @throws CHttpException
     */
    private function getDirUpload()
    {
        $dir = Yii::getPathOfAlias('webroot') . self::DIR_UPLOADS;
        if (!file_exists($dir)){
            if (!mkdir($dir, 0777)){
                throw new CHttpException(500, 'Не удалось создать директорию для загрузки файлов.');
            }
        }
        if (!is_dir($dir) || !is_writable($dir)){
            throw new CHttpException(500, 'Не доступна директория для загрузки файлов.');
        }
        return $dir;
    }
}