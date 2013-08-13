<?php
/**
 * Удаление документа.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class DeleteAction extends CAction
{
    /**
     * Удаление документа.
     */
    public function run($class_name, $id, $type, $file)
    {
        if (Yii::app()->request->isAjaxRequest){
            $path = Yii::app()->params->uploadDocumentDir
                . DIRECTORY_SEPARATOR . Yii::app()->user->getId()
                . DIRECTORY_SEPARATOR . $class_name
                . DIRECTORY_SEPARATOR . $id
                . DIRECTORY_SEPARATOR . $type;

            $filename = $path . DIRECTORY_SEPARATOR . $file;
            if (file_exists($filename)){
                unlink($filename);
//                $ret = array(
//                    'success' => true
//                );
            }
//            else {
//                $ret = array(
//                    'success' => false,
//                    'message' => 'Файл не существует.'
//                );
//            }
            $ret = array(
                'success' => true
            );
            echo CJSON::encode($ret);
        }
    }
}