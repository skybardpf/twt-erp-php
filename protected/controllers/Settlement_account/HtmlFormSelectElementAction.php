<?php
/**
 * Only Ajax. Управление менеджерами счета.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class HtmlFormSelectElementAction extends CAction
{
    /**
     * Only Ajax. Управление менеджерами счета.
     */
    public function run()
    {
        if (Yii::app()->request->isAjaxRequest) {
            try {
                $ids = Yii::app()->request->getPost('ids', '[]');
                $ids = CJSON::decode($ids);
                $data = Individual::model()->listNames();
                foreach ($ids as $pid){
                    if (isset($data[$pid])){
                        unset($data[$pid]);
                    }
                }
                $data[''] = '--- Выберите ---';

                $html = $this->controller->renderPartial(
                    '/settlement_account/_html_form_select_element',
                    array(
                        'data' => $data,
                    ),
                    true
                );
                echo CJSON::encode(
                    array(
                        'success' => true,
                        'html' => $html
                    )
                );
                Yii::app()->end(200);

            } catch (CException $e){
                echo CJSON::encode(
                    array(
                        'success' => false,
                        'message' => $e->getMessage()
                    )
                );
                Yii::app()->end(400);
            }
        }
    }
}