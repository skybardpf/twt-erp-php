<?php
/**
 * Only Ajax. Возращает html строку для вставки в таблицу организаций.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class HtmlRowElementAction extends CAction
{
    /**
     * Только Ajax. Возращает html строку для вставки в таблицу организаций.
     */
    public function run() {
        if (Yii::app()->request->isAjaxRequest) {
            try {
                    if (!isset($_POST['id']) || empty($_POST['id'])){
                    throw new CException('Не указан идентификатор');
                }
                if (!isset($_POST['name']) || empty($_POST['name'])){
                    throw new CException('Не указано название');
                }
                if (!isset($_POST['type']) || !in_array($_POST['type'], array('organization', 'country'))){
                    throw new CException('Не указан тип.');
                }

                $this->controller->renderPartial(
                    '/my_events/_html_row_element',
                    array(
                        'id' => $_POST['id'],
                        'name' => $_POST['name'],
                        'type' => $_POST['type'],
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