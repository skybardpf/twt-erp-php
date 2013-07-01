<?php
/**
 *  Банковские счета -> Форма редактирования банковского счета.
 *  User: Skibardin A.A.
 *  Date: 27.06.13
 *
 *  @var $this       My_OrganizationsController
 *  @var $model      SettlementAccount
 *  @var $form       TbActiveForm
 */
?>

<?php
    Yii::app()->clientScript->registerScriptFile('/static/js/jquery.json-2.4.min.js');

    $this->beginContent('/my_organizations/show');

    echo '<h2>'.($model->primaryKey ? 'Редактирование ' : 'Создание ').'банковского счета</h2>';

    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'    => 'horizontalForm',
        'type'  => 'horizontal',
    ));

    // Опции для JUI селектора даты
    $jui_date_options = array(
        'options'=>array(
            'showAnim'  =>'fold',
            'dateFormat' => 'yy-mm-dd',
        ),
        'htmlOptions'=>array(
            'style'=>'height:20px;'
        )
    );

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=> 'submit',
        'type'      => 'primary',
        'label'     => 'Сохранить'
    ));
    echo '&nbsp;';
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'link',
        'label'      => 'Отмена',
        'url'        => $model->primaryKey
            ? $this->createUrl('settlement', array('action' => 'show', 'id' => $model->primaryKey))
            : $this->createUrl('settlements', array('id' => $this->organization->primaryKey))
    ));

    if ($error) {
        echo CHtml::openTag('div', array('class' => 'alert alert-error')).$error.CHtml::closeTag('div');
    }
    echo $form->errorSummary($model);
?>

<fieldset>
<?php
    echo $form->textFieldRow($model, 's_nom', array('class' => 'span6'));
    echo $form->textFieldRow($model, 'iban', array('class' => 'span6'));
    echo $form->dropDownListRow($model, 'cur', Currencies::getValues());

    echo $form->textFieldRow($model, 'bank', array('class' => 'span6'));
    echo $form->textFieldRow($model, 'bank_name', array('class' => 'span6', 'readonly' => true));

    echo $form->dropDownListRow($model, 'vid', SettlementAccount::getAccountTypes());
    echo $form->dropDownListRow($model, 'service', SettlementAccount::getServiceTypes());
    echo $form->textFieldRow($model, 'name', array('class' => 'span6'));
?>
<?php /** data_open */ ?>
<div class="control-group">
    <label class="control-label" for="SettlementAccount_data_open">
        <?= $model->getAttributeLabel("data_open") . CHtml::tag('span', array('class' => 'required')) .'&nbsp;*&nbsp;'; ?>
    </label>
    <div class="controls">
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
            array(
                'model'     => $model,
                'attribute' => 'data_open'
            ), $jui_date_options
        )); ?>
    </div>
</div>

<?php /** data_closed */ ?>
<div class="control-group">
    <label class="control-label" for="SettlementAccount_data_closed">
        <?= $model->getAttributeLabel("data_closed") . CHtml::tag('span', array('class' => 'required')) .'&nbsp;*&nbsp;'; ?>
    </label>
    <div class="controls">
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
            array(
                'model'     => $model,
                'attribute' => 'data_closed'
            ), $jui_date_options
        )); ?>
    </div>
</div>

<?php
    echo $form->textAreaRow($model, 'address', array('class' => 'span6'));
    echo $form->textAreaRow($model, 'contact', array('class' => 'span6'));
?>

<?php
    /** managing_persons */
    $person = '';
    $p = Individuals::getValues();
    foreach ($model->managing_persons as $pid){
        if (!isset($p[$pid])){
            $person .= $pid;
        } else {
            $person .= CHtml::tag('div', array(
                    'class'     => 'managing_person',
                    'data-pid'  => $pid,
                ),
                CHtml::link($p[$pid], $this->createUrl('/legal/individuals/view/', array('id' => $pid))) .
                '&nbsp;' .
                CHtml::tag('span', array(
                    'class' => 'icon-trash',
                    'style' => 'cursor: pointer;'
                ))
            );
        }
    }
    $model->str_managing_persons = CJSON::encode($model->managing_persons);
    $class = (empty($model->managing_persons) ? 'controls' : 'controls hide');
//    echo $form->textField($model, 'str_managing_persons', array('class' => 'hide'));
    echo $form->hiddenField($model, 'str_managing_persons');
?>
<div class="control-group">
    <label class="control-label" for="SettlementAccount_managing_persons">
        <?= $model->getAttributeLabel("managing_persons") . CHtml::tag('span', array('class' => 'required')) .'&nbsp;*&nbsp;'; ?>
    </label>
    <div class="<?= $class; ?>" id="managing_person_message">
        Добавьте физ. лиц, управляющих счетом
    </div>
    <div class="controls" id='managing_persons'>
        <?= $person; ?>
    </div>
    <div class="controls">
        <button class="btn" id="data-add_managing_person" data-loading-text="..." type="button">Добавить</button>
    </div>
</div>

<?php
    echo $form->radioButtonListInlineRow($model, 'management_method', SettlementAccount::getManagementMethods());
?>
</fieldset>

<?php $this->endWidget(); ?>

<?php
    // Модальное окошко для выбора физ. лица
    $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'dataModal'));
?>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h4><?=Yii::t("menu", "Выберите управляющего счетом")?></h4>
    </div>
    <div class="modal-body"></div>
    <div class="modal-footer">
        <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'label' => Yii::t("menu", "Сохранить"),
                'url'   => '#',
                'htmlOptions' => array('class'=>'button_save', 'data-dismiss'=>'modal'),
            ));

            $this->widget('bootstrap.widgets.TbButton', array(
                'label' => Yii::t("menu", "Отмена"),
                'url'   => '#',
                'htmlOptions' => array('data-dismiss'=>'modal'),
            ));
        ?>
    </div>

<?php $this->endWidget(); ?>
<?php $this->endContent(); ?>

<script>
    $(document).ready(function(){
        $('#managing_persons .managing_person .icon-trash').each(function(i,e){
            $(e).on('click', delete_managing_person);
        });

        /**
         *  Получаем название банка по его идентификатору (БИК или СВИФТ)
         */
        $('#SettlementAccount_bank').change(function(){
            Loading.show();

            var bank_name = $('#SettlementAccount_bank_name');
            $.ajax({
//                type: 'POST',
                dataType: "json",
                url: "<?= CController::createUrl('/legal/settlement_accounts/get_bank_name'); ?>",
//                cache: false,
                data: {
                    'bank': $('#SettlementAccount_bank').val()
                }
            })
            .done(function(data, ret) {
                var res = '';
                if (ret == 'success'){
                    res = data.bank_name
                }
                bank_name.val(res);
//                console.log(arguments);
            })
            .fail(function(a, ret, message) {
                alert(ret + ': ' + message);
                bank_name.val('');
            })
            .always(function(){
                Loading.hide();
            });
        });

        /**
         *  Добавляем управляющего счетом
         */
        $('#dataModal .button_save').live('click', function(){
            var sel = $('#select_managing_person option:selected');
            var pid = sel.val();
            var name = sel.html();
            if (pid == ''){
                alert('Выберите физ. лицо, управляющее счетом');
                return false;
            } else {
                var el = $('#SettlementAccount_str_managing_persons');
                var persons = eval(el.val());

                var ind = persons.indexOf(pid);
                if (ind == -1){
                    persons.push(pid);

                }
                el.val($.toJSON(persons));

                var div_person = $(
                    '<div class="managing_person" data-pid="'+pid+'">' +
                    '<a href="/legal/individuals/view/id/'+pid+'">'+name+'</a>&nbsp;' +
                    '<span class="icon-trash" style="cursor: pointer;"></span>' +
                    '</div>'
                );
                $('#managing_persons').append(div_person);
                div_person.find('.icon-trash').on('click', delete_managing_person);
                $('#managing_person_message').addClass('hide');
            }
        });

        /**
         *  Show modal
         */
        $('#data-add_managing_person').click(function(){
            var button = this;
            $(button).button('loading');

            Loading.show();

            $.ajax({
//                type: 'POST',
//                dataType: "json",
                url: "<?=
                    CController::createUrl(
                        '/legal/settlement_accounts/selected_managing_persons',
                        array(
                            'id' => $model->primaryKey,
                        )
                    );
                 ?>",
                cache: false,
                data: {
                    'selected_ids': $('#SettlementAccount_str_managing_persons').val()
                }
            })
            .done(function(data) {
                $(".modal-body").html(data);
                $(button).button('reset');
                $('#dataModal').modal().css({
                    width: 'auto',
                    'margin-left': function () {
                        return -($(this).width() / 2);
                    }
                });
            })
            .fail(function(a, ret, message) {
                alert(ret + ': ' + message);
            })
            .always(function(){
                Loading.hide();
                $(button).button('reset');
            })
        });
    });

    /**
     *  Удаляем управляющего счетом.
     *  @returns {boolean}
     */
    function delete_managing_person(){
        var target = $(this);
        $('<div>'+'Вы уверены, что хотите удалить управляющего банковским счетом?'+'</div>').dialog({
            modal: true,
            resizable: false,
            title: 'Удаление управляющего счетом',
            buttons: [
                {
                    text: "Удалить",
                    class: 'btn btn-danger',
                    click: function(event){
                        var button = $(event.target);
                        var div_person = target.parent('.managing_person');
//                        console.log(div_person);
                        var dialog = $(this);

                        button.attr('disabled', 'disabled');
                        Loading.show();

                        var el = $('#SettlementAccount_str_managing_persons');
                        var persons = eval(el.val());
                        var ind = persons.indexOf(div_person.data('pid'));

//                        console.log(div_person.data('pid'));
//                        console.log(ind);

                        if (ind != -1){
                            persons.splice(ind, 1);
                            el.val($.toJSON(persons));
                            if (!persons.length){
                                $('#managing_person_message').removeClass('hide');
                            }
//                            target.off('click');
                            div_person.remove();
                        }
                        Loading.hide();
                        dialog.dialog('destroy');
                    }
                },{
                    text: 'Отмена',
                    class: 'btn',
                    click: function(){
                        $(this).dialog('destroy');
                    }
                }
            ]
        });
        return false;
    }
</script>