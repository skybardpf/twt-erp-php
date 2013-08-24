<?php
/**
 * Only Ajax. Возращает html строку для вставки в таблицу подписантов и довереностей.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class HtmlRowElementAction extends CAction
{
    /**
     * Только Ajax. Возращает html строку для вставки в таблицу подписантов и довереностей.
     */
    public function run() {
        if (Yii::app()->request->isAjaxRequest) {
            try {
                if (!isset($_POST['doc_id']) || empty($_POST['doc_id']) || !ctype_digit($_POST['doc_id'])){
                    throw new CException('Не указан идентификатор доверености');
                }
                $doc = PowerAttorneysLE::loadModel($_POST['doc_id']);
                $person = Individual::loadModel($doc->id_lico);

                echo CJSON::encode(
                    array(
                        'success' => true,
                        'doc_id' => $doc->primaryKey,
                        'person_id' => $person->primaryKey,
                        'html' => $this->controller->renderPartial(
                            '/contractor/_html_row_element',
                            array(
                                'person_id' => $person->primaryKey,
                                'doc_id' => $doc->primaryKey,
                                'person_name' => $person->family.' '.$person->name.' '.$person->parent_name,
                                'doc_name' => $doc->name,
                            ),
                            true
                        )
                    )
                );
                Yii::app()->end();

            } catch (CException $e){
                echo CJSON::encode(
                    array(
                        'success' => false,
                        'error' => $e->getMessage()
                    )
                );
                Yii::app()->end();
            }
        }
    }
}