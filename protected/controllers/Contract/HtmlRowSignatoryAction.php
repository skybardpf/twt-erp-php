<?php
/**
 * Only Ajax. Возращает html. Новую строка для вставки в таблицу подписантов.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class HtmlRowSignatoryAction extends CAction
{
    /**
     * Only Ajax. Возращает html. Новую строка для вставки в таблицу подписантов.
     */
    public function run()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $type = Yii::app()->request->getPost('type', '');
            if (!in_array($type, array('organization_signatories', 'contractor_signatories'))){
                echo 'Передан неизвестный тип подписанта';
                Yii::app()->end();
            }
            if (!isset($_POST['id']) || empty($_POST['id'])){
                echo 'Не указан идентификатор подписанта';
                Yii::app()->end();
            }
            if (!isset($_POST['name']) || empty($_POST['name'])){
                echo 'Не указано ФИО подписанта';
                Yii::app()->end();
            }

            $this->controller->renderPartial(
                '/contract/_new_row_signatory',
                array(
                    'id' => $_POST['id'],
                    'name' => $_POST['name'],
                    'type' => $type,
                ),
                false
            );
            Yii::app()->end();
        }
    }
}