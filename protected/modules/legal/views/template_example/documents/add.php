<?php
/**
 * @var $this Template_exampleController
 */

$model = new  PowerAttorneyForOrganization();
?>

    <h1>Редактирование документа</h1>

<?php /** @var TbActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'horizontalForm',
    'type'=>'horizontal',
)); ?>

<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Сохранить')); ?>
&nbsp;
<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Отмена')); ?>

    <fieldset>

        <?php echo $form->dropDownListRow($model, 'id_lico'); ?>
        <?php echo $form->textFieldRow($model, 'nom'); ?>
        <?php echo $form->textFieldRow($model, 'name'); ?>
        <?php echo $form->dropDownListRow($model, 'typ_doc'); ?>
        <div class="control-group ">
            <label class="control-label" for="PowerAttorneysLE_contract_types">Виды договоров</label>
            <div class="bordered controls">
                <div class="left" data-bank_accounts="1">
                    <div style="display:block;" data-account_resident="0" class="bank_account">
                        <a data-bank_account_link="15" data-account_resident="0" href="/1/update_account_company?resident=0&amp;account_id=15" class="">
                            Договор купли-продажи
                        </a>
                        <a data-bank_account_delete="15" data-account_resident="0" href="/companies/delete_account/account_id/15" class="pull-right icon-remove">
                        </a>
                    </div>
                </div>
                <div>
                    <a style="display:inline;" data-bank_account_link="" data-account_resident="0" href="/1/update_account_company?resident=0" class="btn btn-primary">
                        Добавить
                    </a>
                </div>
            </div>
        </div>

        <div class="control-group ">
            <label for="PowerAttorneysLE_expire" class="control-label">Срок действия</label>
            <div class="controls">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                    'name'=>' PowerAttorneyForOrganization[expire]',
                    // additional javascript options for the date picker plugin
                    'options'=>array(
                        'showAnim'=>'fold',
                        'id' => 'PowerAttorneysLE_expire'
                    ),
                    'htmlOptions'=>array(
                        'style'=>'height:20px;'
                    ),
                ));?>
            </div>
        </div>
        <?php echo $form->fileFieldRow($model, 'scans'); ?>
        <?php echo $form->fileFieldRow($model, 'e_ver'); ?>

    </fieldset>

<?php $this->endWidget(); ?>