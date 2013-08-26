<?php
/**
 *  Банковские счета. Просмотр информации о банковском счете.
 *
 *  User: Skibardin A.A.
 *  Date: 02.07.13
 *
 *  @var $this          Settlement_accountsController
 *  @var $model         SettlementAccount
 *  @var $organization  Organizations
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
    $p = Individual::getValues();
    foreach ($model->managing_persons as $pid){
        if (isset($p[$pid])){
            $person .= CHtml::link($p[$pid], $this->createUrl('/legal/Individual/view/', array('id' => $pid)));
        } else {
            $person .= $pid;
        }
        $person .= '<br/>';
    }

    $cur = Currencies::getValues();
    $cur = (isset($cur[$model->cur])) ? $cur[$model->cur] : 'Не указано';

	$this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => $model,
		'attributes' => array(
            array('name' => 's_nom',        'label' => 'Номер счета'),
            array('name' => 'iban',         'label' => 'IBAN'),
            array('name' => 'cur_name',     'label' => 'Валюта', 'value' => $cur),
            array('name' => 'bank_name',    'label' => 'Банк'),
            array('name' => 'vid',          'label' => 'Вид счета'),
            array('name' => 'service',      'label' => 'Вид обслуживания счета'),
			array('name' => 'name',         'label' => 'Представление'),
            array('name' => 'data_open',    'label' => 'Дата открытия'),
//
            array('name' => 'address',      'label' => 'Адрес отделения'),
            array('name' => 'contact',      'label' => 'Контакты в отделении'),
//
            array(
                'name'  => 'list_managing_persons',
                'type'  => 'raw',
                'label' => 'Управляющие персоны',
                'value' => $person
            ),
            array('name' => 'management_method', 'label' => 'Метод управления'),
		))
	);
?>
</div>
