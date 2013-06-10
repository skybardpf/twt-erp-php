<?php
/**
 * @var $this Template_exampleController
 */

$model = new Beneficiary();
?>

    <h1>Редактирование бенефициара</h1>

<?php /** @var TbActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'horizontalForm',
    'type'=>'horizontal',
)); ?>

<?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType'=>'link',
    'type'=>'primary',
    'label'=>'Сохранить',
    'url' => Yii::app()->getController()->createUrl("benefit_show", array('id'=>$id))
    )); ?>
&nbsp;
<?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType'=>'link',
    'label'=>'Отмена',
    'url' => Yii::app()->getController()->createUrl("benefit_show", array('id'=>$id))
)); ?>

    <fieldset>

        <div class="control-group ">
            <label for="Beneficiary_fiz_yur_lico" class="control-label">Физическое или юридическое лицо</label>
            <div class="control-add pull-right">
                <a href="<?=Yii::app()->getController()->createUrl("person_add")?>">Добавить физическое лицо</a>
                <br>
                <a href="<?=Yii::app()->getController()->createUrl("add")?>">Добавить юридическое лицо</a>
            </div>
            <div class="controls">
                <select name="Beneficiary[fiz_yur_lico]" class="input-small notsosmall" id="Beneficiary_fiz_yur_lico">
                    <option>Лицо физическое</option>
                    <option>Лицо юридическое</option>
                </select>
            </div>
        </div>

        <?php echo $form->dropDownListRow($model, 'role'); ?>

        <div class="control-group ">
            <label class="control-label" for="SettlementAccount_bank">Величина пакета акций</label>
            <div class="controls">
                <input id="Beneficiary_percent" class="input-small notsosmall" type="text" name="Beneficiary[percent]"> %
            </div>
        </div>

        <div class="control-group ">
            <label class="control-label" for="Beneficiary_cost">Номинальная стоимость пакета акций</label>
            <div class="controls">
                <input id="Beneficiary_cost" class="input-small notsosmall" type="text" name="Beneficiary[cost]">
                <select id="Beneficiary_cur" class="input-small notsosmall" name="Beneficiary[cur]">
                    <option>EUR</option>
                    <option>USD</option>
                    <option>RUR</option>
                    <option>UAH</option>
                    <option>AUD</option>
                </select>
            </div>
        </div>

        <?php echo $form->textAreaRow($model, 'add_info'); ?>

    </fieldset>

<?php $this->endWidget(); ?>