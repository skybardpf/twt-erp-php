<?php
/**
 * Only Ajax. Получить список представлений в зависимости от пришедших данных.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class GetTypeViewAction extends CAction
{
    /**
     * Only Ajax. Получить список представлений в зависимости от пришедших данных.
     */
    public function run()
    {
        if (Yii::app()->request->isAjaxRequest) {
            try {
                $request = Yii::app()->request;
                $num = $request->getPost('number_account', '');
                $type = $request->getPost('type_account', '');
                $name = $request->getPost('bank_name', '');
                $type_view_id = $request->getPost('type_view_id', '---');

                $model = new SettlementAccount();
                $model->name = $type_view_id;
                $model->s_nom = $num;
                $model->bank_name = $name;
                $model->type_account = $type;

                $html = $this->controller->renderPartial(
                    '_html_type_view',
                    array(
                        'model' => $model,
                    ),
                    true
                );

                echo CJSON::encode(array(
                    'success' => true,
                    'html' => $html
                ));
                Yii::app()->end(200);
            } catch(CException $e){
                echo CJSON::encode(array(
                    'success' => false
                ));
                Yii::app()->end(400);
            }
        }
    }
}