<?php
/**
 * Возращает Html.
 * Получить список представлений в зависимости от пришедших данных.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Settlement_accountController $this
 * @var SettlementAccount $model
 */

$data = $model->getTypeView();
$data['---'] = '--- Шаблон не выбран ---';
?>

<div class="control-group ">
    <?= CHtml::activeLabelEx($model, 'name', array('class'=>'control-label')); ?>
    <div class="controls">
        <?= CHtml::activeDropDownList($model, 'name', $data); ?>
        <span class="help-inline error" id="SettlementAccount_name_em_" style="display: none"></span>
    </div>
</div>