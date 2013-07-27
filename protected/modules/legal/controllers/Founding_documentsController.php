<?php
/**
 *  Управление учредительными документами.
 *
 *  User: Skibardin A.A.
 *  Date: 03.07.13
 */
class Founding_documentsController extends Controller {
    public $layout = 'inner';
    public $menu_current = 'legal';

    /**
     * Создание учредительного документа
     *
     * @param  string $org_id
     *
     * @throws CHttpException
     */
    public function actionAdd($org_id)
    {
        /** @var $org Organizations */
        $org = Organizations::model()->findByPk($org_id);
        if (!$org) {
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }

        $doc = new FoundingDocument();
        $doc->id_yur    = $org->primaryKey;
        $doc->type_yur  = "Организации";
        $doc->from_user = true;
        $doc->user      = SOAPModel::USER_NAME;

        $error = '';
        if ($_POST && !empty($_POST['FoundingDocument'])) {
            $doc->setAttributes($_POST['FoundingDocument']);
            if ($doc->validate()) {
                try {
                    $doc->save();
                    $this->redirect($this->createUrl('documents/list', array('org_id' => $org->primaryKey)));
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }

        $this->render('/my_organizations/show', array(
            'content' => $this->renderPartial('/founding_documents/form',
                array(
                    'model'         => $doc,
                    'error'         => $error,
                    'organization'  => $org
                ), true),
            'organization' => $org,
            'cur_tab' => 'documents',
        ));
    }

    /**
     * Редактирование учредительного документа
     *
     * @param   string $id
     *
     * @throws  CHttpException
     */
    public function actionEdit($id)
    {
        /** @var $doc FoundingDocument */
        $doc = FoundingDocument::model()->findByPk($id);
        if (!$doc) {
            throw new CHttpException(404, 'Не найден учредительный документ');
        }
        if ($doc->type_yur != 'Организации') {
            throw new CHttpException(404, 'У документа неверный тип для данной страницы');
        }

        /** @var $org Organizations */
        $org = Organizations::model()->findByPk($doc->id_yur);
        if (!$org) {
            throw new CHttpException(404, 'Юр.лицо данного документа не получено.');
        }

        $doc->user = SOAPModel::USER_NAME;
        $error = '';
        if ($_POST && !empty($_POST['FoundingDocument'])) {
            $doc->setAttributes($_POST['FoundingDocument']);
            if ($doc->validate()) {
                try {
                    $doc->save();
					$this->redirect($this->createUrl('view', array('id' => $id)));
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }

//        $scans = new UploadScan;
//        Yii::import( "xupload.models.XUploadForm" );
//        $photos = new XUploadForm;

        $this->render('/my_organizations/show', array(
            'content' => $this->renderPartial('/founding_documents/form',
                array(
                    'model'         => $doc,
                    'error'         => $error,
                    'organization'  => $org
                ), true),
            'organization' => $org,
            'cur_tab' => 'documents',
        ));
    }

    /**
     *  Просмотр учредительного документа
     *
     *  @param   string $id
     *
     *  @throws  CHttpException
     */
    public function actionView($id)
    {
        /** @var $doc FoundingDocument */
        $doc = FoundingDocument::model()->findByPk($id);
        if (!$doc) {
            throw new CHttpException(404, 'Не найден учредительный документ');
        }
        if ($doc->type_yur != 'Организации') {
            throw new CHttpException(404, 'У документа неверный тип для данной страницы');
        }

        /** @var $org Organizations */
        $org = Organizations::model()->findByPk($doc->id_yur);
        if (!$org) {
            throw new CHttpException(404, 'Юр.лицо данного документа не получено.');
        }

        $this->render('/my_organizations/show', array(
            'content' => $this->renderPartial('/founding_documents/show',
                array(
                    'model'         => $doc,
                    'organization'  => $org
                ), true),
            'organization' => $org,
            'cur_tab' => 'documents',
        ));
    }

    /**
     *  Удаление учредительного документа
     *
     *  @param   string $id
     *
     *  @throws  CHttpException
     */
    public function actionDelete($id)
    {
        /** @var $doc FoundingDocument */
        $doc = FoundingDocument::model()->findByPk($id);
        if (!$doc) {
            throw new CHttpException(404, 'Не найден учредительный документ');
        }
        if ($doc->type_yur != 'Организации') {
            throw new CHttpException(404, 'У документа неверный тип для данной страницы');
        }

        /** @var $org Organizations */
        $org = Organizations::model()->findByPk($doc->id_yur);
        if (!$org) {
            throw new CHttpException(404, 'Юр.лицо данного документа не получено.');
        }

        if (Yii::app()->request->isAjaxRequest) {
            $ret = array();
            try {
                $doc->delete();
            } catch (Exception $e) {
                $ret['error'] = $e->getMessage();
            }
            echo CJSON::encode($ret);
            Yii::app()->end();
        } else {
            if (isset($_POST['result'])) {
                switch ($_POST['result']) {
                    case 'yes':
                        if ($doc->delete()) {
                            $this->redirect($this->createUrl('documents/list', array('org_id' => $org->primaryKey)));
                        } else {
                            throw new CHttpException(500, 'Не удалось удалить учредительный документ');
                        }
                    break;
                    default:
                        $this->redirect($this->createUrl('view', array('id' => $doc->primaryKey)));
                    break;
                }
            }
//            $this->render('delete_founding', array('model' => $doc));
        }
    }

    /**
     * Загрузка файлов для учредительного документа
     *
     * @param   string $id
     *
     * @throws  CHttpException
     */
    public function actionUpload_founding($id)
    {
//        Yii::import( "xupload.models.XUploadForm" );
//
//        //Here we define the paths where the files will be stored temporarily
//        $path = realpath( Yii::app( )->getBasePath()."/../../upload/tmp/" )."/";
//        $publicPath = Yii::app( )->getBaseUrl()."/upload/";
//
//        //This is for IE which doens't handle 'Content-type: application/json' correctly
//        header( 'Vary: Accept' );
//        if( isset( $_SERVER['HTTP_ACCEPT'] )
//            && (strpos( $_SERVER['HTTP_ACCEPT'], 'application/json' ) !== false) ) {
//            header( 'Content-type: application/json' );
//        } else {
//            header( 'Content-type: text/plain' );
//        }
//
//        //Here we check if we are deleting and uploaded file
//        if( isset( $_GET["_method"] ) ) {
//            if( $_GET["_method"] == "delete" ) {
//                if( $_GET["file"][0] !== '.' ) {
//                    $file = $path.$_GET["file"];
//                    if( is_file( $file ) ) {
//                        unlink( $file );
//                    }
//                }
//                echo json_encode( true );
//            }
//        } else {
//            $model = new XUploadForm;
//            $model->file = CUploadedFile::getInstance( $model, 'file' );
//            //We check that the file was successfully uploaded
//            if( $model->file !== null ) {
//                //Grab some data
//                $model->mime_type = $model->file->getType( );
//                $model->size = $model->file->getSize( );
//                $model->name = $model->file->getName( );
//                //(optional) Generate a random name for our file
//                $filename = md5( Yii::app( )->user->id.microtime( ).$model->name);
//                $filename .= ".".$model->file->getExtensionName( );
//                if( $model->validate( ) ) {
//                    //Move our file to our temporary dir
//                    $model->file->saveAs( $path.$filename );
//                    chmod( $path.$filename, 0777 );
//                    //here you can also generate the image versions you need
//                    //using something like PHPThumb
//
//
//                    //Now we need to save this path to the user's session
//                    if( Yii::app( )->user->hasState( 'images' ) ) {
//                        $userImages = Yii::app( )->user->getState( 'images' );
//                    } else {
//                        $userImages = array();
//                    }
//                    $userImages[] = array(
//                        "path" => $path.$filename,
//                        //the same file or a thumb version that you generated
//                        "thumb" => $path.$filename,
//                        "filename" => $filename,
//                        'size' => $model->size,
//                        'mime' => $model->mime_type,
//                        'name' => $model->name,
//                    );
//                    Yii::app( )->user->setState( 'images', $userImages );
//
//                    //Now we need to tell our widget that the upload was succesfull
//                    //We do so, using the json structure defined in
//                    // https://github.com/blueimp/jQuery-File-Upload/wiki/Setup
//                    echo json_encode( array( array(
//                        "name" => $model->name,
//                        "type" => $model->mime_type,
//                        "size" => $model->size,
//                        "url" => $publicPath.$filename,
//                        "thumbnail_url" => $publicPath."thumbs/$filename",
//                        "delete_url" => $this->createUrl( "upload", array(
//                            "_method" => "delete",
//                            "file" => $filename
//                        ) ),
//                        "delete_type" => "POST"
//                    ) ) );
//                } else {
//                    //If the upload failed for some reason we log some data and let the widget know
//                    echo json_encode( array(
//                        array( "error" => $model->getErrors( 'file' ),
//                        ) ) );
//                    Yii::log( "XUploadAction: ".CVarDumper::dumpAsString( $model->getErrors( ) ),
//                        CLogger::LEVEL_ERROR, "xupload.actions.XUploadAction"
//                    );
//                }
//            } else {
//                throw new CHttpException( 500, "Could not upload file" );
//            }
//        }
    }
}