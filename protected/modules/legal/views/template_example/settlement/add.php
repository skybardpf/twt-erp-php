<?php
/**
 * @var $this Template_exampleController
 */

$model = new SettlementAccount();
?>

    <h1>Редактирование лицевого счёта</h1>

<?php /** @var TbActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'horizontalForm',
    'type'=>'horizontal',
)); ?>

<?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType'=>'link',
    'type'=>'primary',
    'label'=>'Сохранить',
    'url' => Yii::app()->getController()->createUrl("settlement_show", array('id'=>$id))
)); ?>
&nbsp;
<?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType'=>'link',
    'label'=>'Отмена',
    'url' => Yii::app()->getController()->createUrl("settlement_show", array('id'=>$id))
)); ?>

    <fieldset>

        <?php echo $form->textFieldRow($model, 's_nom'); ?>

        <div class="control-group ">
            <label class="control-label" for="SettlementAccount_bank">Банк</label>
            <div class="controls">
                <label class="control-label small" for="SettlementAccount_bank">SWIFT-код</label>
                <input id="SettlementAccount_bank_SWIFT" class="input-small" type="text" name="SettlementAccount[bank_SWIFT]">
                <label class="control-label small" for="SettlementAccount_bank">Название</label>
                <input id="SettlementAccount_bank" class="input-small" type="text" name="SettlementAccount[bank]">
            </div>
        </div>


        <?php echo $form->textFieldRow($model, 'vid'); ?>
        <?php echo $form->textFieldRow($model, 'name'); ?>
        <?php echo $form->textFieldRow($model, 'iban'); ?>

        <div class="control-group ">
            <label for="SettlementAccount_data_open" class="control-label">Дата открытия</label>
            <div class="controls">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                    'name'=>'SettlementAccount[data_open]',
                    // additional javascript options for the date picker plugin
                    'options'=>array(
                        'showAnim'=>'fold',
                        'id' => 'SettlementAccount_data_open'
                    ),
                    'htmlOptions'=>array(
                        'style'=>'height:20px;'
                    ),
                ));?>
            </div>
        </div>

        <div class="control-group ">
            <label for="SettlementAccount_data_closed" class="control-label">Дата закрытия</label>
            <div class="controls">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                    'name'=>'SettlementAccount[data_closed]',
                    // additional javascript options for the date picker plugin
                    'options'=>array(
                        'showAnim'=>'fold',
                        'id' => 'SettlementAccount_data_closed'
                    ),
                    'htmlOptions'=>array(
                        'style'=>'height:20px;'
                    ),
                ));?>
            </div>
        </div>

        <div class="control-group ">
            <label class="control-label" for="SettlementAccount_bank">Банк-корреспондент</label>
            <div class="controls">
                <label class="control-label small" for="SettlementAccount_bank">SWIFT-код</label>
                <input id="SettlementAccount_bank_SWIFT" class="input-small" type="text" name="SettlementAccount[bank_SWIFT]">
                <label class="control-label small" for="SettlementAccount_bank">Название</label>
                <input id="SettlementAccount_bank" class="input-small" type="text" name="SettlementAccount[bank]">
            </div>
        </div>

        <?php echo $form->textFieldRow($model, 'corr_account'); ?>
        <?php echo $form->dropDownListRow($model, 'cur'); ?>
        <?php echo $form->textFieldRow($model, 'recomend'); ?>
        <?php echo $form->textAreaRow($model, 'contact'); ?>

        <div class="control-group ">
            <label class="control-label" for="PowerAttorneysLE_contract_types">Управляющие персоны</label>
            <div class="bordered controls">
                <div class="left" data-bank_accounts="1">
                    <div style="display:block;" data-account_resident="0" class="bank_account">
                        <a data-bank_account_link="15" data-account_resident="0" href="<?=Yii::app()->getController()->createUrl("person_show", array("id" => 5))?>" class="">
                            Малхасян Геворк Рубенович
                        </a>
                        <a data-bank_account_delete="15" data-account_resident="0" href="#" class="pull-right icon-remove">
                        </a>
                    </div>
                </div>
                <div class="left" data-bank_accounts="2">
                    <div style="display:block;" data-account_resident="0" class="bank_account">
                        <a data-bank_account_link="15" data-account_resident="0" href="<?=Yii::app()->getController()->createUrl("person_show", array("id" => 1))?>" class="">
                            Померанцев Павел Вячеславович
                        </a>
                        <a data-bank_account_delete="15" data-account_resident="0" href="#" class="pull-right icon-remove">
                        </a>
                    </div>
                </div>
                <div>
                    <a style="display:inline;" data-bank_account_link="" data-account_resident="0" href="#" class="btn btn-primary">
                        Добавить
                    </a>
                </div>
            </div>
        </div>

    </fieldset>

<?php $this->endWidget(); ?>