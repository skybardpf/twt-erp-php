<?php
/**
 * Банковские счета. Просмотр информации о банковском счете.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Settlement_accountController    $this
 * @var SettlementAccount               $model
 * @var Organization                    $organization
 */
?>

<h2>Банковский счет</h2>

<?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'link',
        'type'       => 'success',
        'label'      => 'Редактировать',
        'url'        => $this->createUrl("edit", array('id' => $model->primaryKey))
    ));

    if (!$model->deleted) {
        echo "&nbsp;";
        Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/delete_item.js');

        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'    => 'submit',
            'type'          => 'danger',
            'label'         => 'Удалить',
            'htmlOptions'   => array(
                'data-question'     => 'Вы уверены, что хотите удалить данный документ?',
                'data-title'        => 'Удаление документа',
                'data-url'          => $this->createUrl('delete', array('id' => $model->primaryKey)),
                'data-redirect_url' => $this->createUrl('list', array('org_id' => $organization->primaryKey)),
                'data-delete_item_element' => '1'
            )
        ));
    }
?>

<br/><br/>
<div>

<?php
    $person = '';
    $p = Individual::model()->listNames($model->forceCached);
    foreach ($model->managing_persons as $pid){
        if (isset($p[$pid])){
            $person .= '&bull;&nbsp;'.CHtml::link($p[$pid], $this->createUrl('individual/view/', array('id' => $pid)));
        } else {
            $person .= $pid;
        }
        $person .= '<br/>';
    }
    $currencies = Currency::model()->listNames($model->forceCached);
    $management_method = SettlementAccount::getManagementMethods();
    $model->management_method = (isset($management_method[$model->management_method])) ? $management_method[$model->management_method] : '---';

	$this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => $model,
		'attributes' => array(
            array(
                'name' => 's_nom',
                'label' => 'Номер счета'
            ),
            array(
                'name' => 'iban',
                'label' => 'IBAN'
            ),
            array(
                'name' => 'cur_name',
                'label' => 'Валюта',
                'value' => (isset($currencies[$model->currency])) ? $currencies[$model->currency] : '---'
            ),
            array(
                'name' => 'bank_name',
                'label' => 'Банк'
            ),
            array(
                'name' => 'type_account',
                'label' => 'Вид счета'
            ),
            array(
                'name' => 'type_service',
                'label' => 'Вид обслуживания счета'
            ),
			array(
                'name' => 'typeView',
                'label' => 'Представление'
            ),
            array(
                'name' => 'data_open',
                'label' => 'Дата открытия'
            ),
            array(
                'name' => 'correspondent_bank_name',
                'label' => 'Банк-корреспондент'
            ),
            array(
                'name' => 'address',
                'label' => 'Адрес отделения'
            ),
            array(
                'name' => 'contact',
                'label' => 'Контакты в отделении'
            ),
            array(
                'name'  => 'managing_persons',
                'type'  => 'raw',
                'label' => 'Управляющие персоны',
                'value' => $person
            ),
            array(
                'name' => 'management_method',
                'label' => 'Метод управления',
            ),
		))
	);
?>
</div>
