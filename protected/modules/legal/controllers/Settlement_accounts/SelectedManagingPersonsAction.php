<?php
/**
 * Only Ajax. Управление менеджерами счета.
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class SelectedManagingPersonsAction extends CAction
{
    /**
     * Only Ajax. Управление менеджерами счета.
     * @param string $selected_ids
     */
    public function run($selected_ids)
    {
        if (Yii::app()->request->isAjaxRequest) {
            try {
                $selected_ids = CJSON::decode($selected_ids);
                $data = Individual::getValues();
                foreach ($selected_ids as $pid){
                    if (isset($data[$pid])){
                        unset($data[$pid]);
                    }
                }
                $data = array_merge(array('' => 'Выберите'), $data);

                $this->controller->renderPartial(
                    '/settlement_accounts/select_managing_persons',
                    array(
                        'data' => $data,
                    ),
                    false
                );

            } catch (CException $e){
                echo $e->getMessage();
            }
        }
    }
}