<?php
/**
 * @var $this Template_exampleController
 */
?>
<h1>Новое мероприятие</h1>

<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Сохранить')); ?>
    &nbsp;
<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Отмена')); ?>

<form id="horizontalForm" class="form-horizontal" method="post" action="/legal/template_example/document_add">
    <fieldset>
        <div class="control-group ">
            <label class="control-label" for="123">Название</label>
            <div class="controls">
                <input id="123" type="text" name="123">
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label" for="567">Тип</label>
            <div class="controls">
                <div class="pull-left" style="margin-top: 3px;">
                    <label><input type="radio" name="567" style="margin-top: 0;"> для юридического лица</label>
                </div>
                <div class="pull-left" style="padding-left: 15px;margin-top: 3px;">
                    <label><input type="radio" name="567" style="margin-top: 0;"> для юрисдикции</label>
                </div>
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label" for="234">Юридическое лицо</label>
            <div class="controls">
                <select id="234" name="234"> </select>
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label" for="345">Первая дата</label>
            <div class="controls">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                    'name'=>'OrganizationPowerAttorney[expire]',
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
        <div class="control-group ">
            <label class="control-label" for="456">Периодичность</label>
            <div class="controls">
                <select id="456" name="456"> </select>
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label" for="678">Описание</label>
            <div class="controls">
                <textarea id="678" name="678"> </textarea>
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label" for="789">Прикреплённые файлы</label>
            <div class="controls">
                <input type="file" id="789" name="789">
            </div>
        </div>
    </fieldset>
</form>