<?php
/**
 * Only Ajax. Возращает html строку для вставки в таблицу подписантов и довереностей.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class HtmlRowElementAction extends CAction
{
    /**
     * Только Ajax. Возращает html строку для вставки в таблицу подписантов и довереностей.
     */
    public function run() {
        if (Yii::app()->request->isAjaxRequest) {
            try {
                if (!isset($_POST['person_id']) || empty($_POST['person_id'])){
                    throw new CException('Не указан идентификатор подписанта');
                }
                if (!isset($_POST['doc_id']) || empty($_POST['doc_id'])){
                    throw new CException('Не указан идентификатор доверености');
                }
                if (!isset($_POST['person_name']) || empty($_POST['person_name'])){
                    throw new CException('Не указано ФИО подписанта');
                }
                if (!isset($_POST['doc_name']) || empty($_POST['doc_name'])){
                    throw new CException('Не указано название доверености');
                }

                $this->controller->renderPartial(
                    '/contractor/_html_row_element',
                    array(
                        'person_id' => $_POST['person_id'],
                        'doc_id' => $_POST['doc_id'],
                        'person_name' => $_POST['person_name'],
                        'doc_name' => $_POST['doc_name'],
                    ),
                    false
                );
                Yii::app()->end();

            } catch (CException $e){
                echo $e->getMessage();
                Yii::app()->end();
            }
        }
    }
}