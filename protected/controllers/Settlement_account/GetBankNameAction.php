<?php
/**
 * Only Ajax. Получить название банка по его БИК/SWIFT
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class GetBankNameAction extends CAction
{
    /**
     * Only Ajax. Получить название банка по его БИК/SWIFT
     * @param string $bank
     */
    public function run($bank)
    {
        if (Yii::app()->request->isAjaxRequest) {
            echo CJSON::encode(array(
                'bank_name' => SettlementAccount::getBankName($bank)
            ));
            Yii::app()->end();
        }
    }
}