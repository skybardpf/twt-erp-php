<?php
/**
 * @var $this Controller
 * @var $model PowerAttorneysLE
 */
?>
<h1><?=($model->id ? 'Редактирование доверенности' : 'Создание доверенности')?></h1>

<?php /** @var TbActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'horizontalForm',
    'type'=>'horizontal',
));
// Опции для JUI селектора даты
$jui_date_options = array(
	'options'=>array(
		'showAnim'=>'fold',
		'dateFormat' => 'yy-mm-dd',
	),
	'htmlOptions'=>array(
		'style'=>'height:20px;'
	)
)
?>

	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Сохранить')); ?>
	&nbsp;
	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Отмена')); ?>

    <fieldset>
        <?php echo $form->dropDownListRow($model, 'id_lico', $individuals); ?>
        <?php echo $form->textFieldRow($model, 'nom'); ?>
        <?php echo $form->textFieldRow($model, 'name'); ?>
        <?php echo $form->dropDownListRow($model, 'typ_doc', PowerAttorneysLE::getDocTypes()); ?>
	    <?php /*
        <?php // sert_date ?>
	    <div class="control-group ">
            <label class="control-label" for="PowerAttorneysLE_contract_types"><?= $model->getAttributeLabel("sert_date"); ?></label>
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
	    */?>
        <?php // date ?><div class="control-group ">
            <label for="PowerAttorneysLE_expire" class="control-label"><?= $model->getAttributeLabel('date'); ?></label>
            <div class="controls">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array_merge($jui_date_options, array(
			            'model' => $model, 'attribute' => 'date',
					)
                ));?>
            </div>
        </div>
	    <?php // loaded ?><div class="control-group ">
            <label for="PowerAttorneysLE_loaded" class="control-label"><?= $model->getAttributeLabel('loaded'); ?></label>
            <div class="controls">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array_merge($jui_date_options, array(
			            'model' => $model, 'attribute' => 'loaded',
		            )
	            ));?>
            </div>
        </div>
	    <?php // expire ?><div class="control-group ">
            <label for="PowerAttorneysLE_expire" class="control-label"><?= $model->getAttributeLabel('expire'); ?></label>
            <div class="controls">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array_merge($jui_date_options, array(
			            'model' => $model, 'attribute' => 'expire',
		            )
	            ));?>
            </div>
        </div>
	    <?php // break ?><div class="control-group ">
            <label for="PowerAttorneysLE_break" class="control-label"><?= $model->getAttributeLabel('break'); ?></label>
            <div class="controls">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array_merge($jui_date_options, array(
			            'model' => $model, 'attribute' => 'break',
		            )
	            ));?>
            </div>
        </div>
        <?php echo $form->fileFieldRow($model, 'scans'); ?>
        <?php echo $form->fileFieldRow($model, 'e_ver'); ?>
        <?php echo $form->textAreaRow($model, 'comment'); ?>

    </fieldset>
<?php $this->endWidget(); ?>
<?php /*
  * Структура входных данных:
*	id_yur – идентификатор организации, по которой выписана доверенность (обязательный)
*	name – наименование
*	date – дата доверенности (дата)
*	nom – номер доверенности
*	typ_doc – вид доверенности («Генеральная», «Свободная», «ПоВидамДоговоров»)
*	id_lico – идентификатор физлица, на которое выписана доверенность
*	loaded - дата загрузки доверенности (дата)
*	expire – дата окончания действия доверенности (дата)
*	break – дата досрочного окончания действия доверенности (дата)
*	e_ver – ссылка на электронную версию доверенности
*	scans – массив строк-ссылок на сканы доверенности
*	comment – комментарий

o	from_user – признак того, что доверенность загружена пользователем
o	user – идентификатор пользователя
o	contract_types  – массив строк-идентификаторов видов договоров, на которые распространяется доверенность
  */?>