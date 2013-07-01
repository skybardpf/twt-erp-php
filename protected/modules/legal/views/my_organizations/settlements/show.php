<?php
/**
 *  Банковские счета. Информация о банковском счете.
 *
 *  User: Skibardin A.A.
 *  Date: 27.06.13
 *
 *  @var $this       My_organizationsController
 *  @var $acc        SettlementAccount
 */
?>

<h2>Банковский счет</h2>
<!--<a href="--><?//=$this->createUrl('settlements', array('id' => $this->organization->primaryKey))?><!--">Назад к списку</a>-->

<?
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'link',
    'type'       => 'success',
    'label'      => 'Редактировать',
    'url'        => $this->createUrl("settlement", array('action' => 'update', 'id' => $acc->primaryKey))
));

if (!$acc->deleted) {
    echo "&nbsp;";
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/delete_item.js');

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'    => 'submit',
        'type'          => 'danger',
        'label'         => 'Удалить',
        'htmlOptions'   => array(
            'data-question'     => 'Вы уверены, что хотите удалить данный документ?',
            'data-title'        => 'Удаление документа',
            'data-url'          => $this->createUrl('settlement', array('action' => 'delete','id' => $acc->primaryKey)),
            'data-redirect_url' => $this->createUrl('settlements', array('id' => $this->organization->primaryKey)),
            'data-delete_item_element' => '1'
        )
    ));
}
?>
<br/><br/>
<div>
<?php
    $person = '';
    $p = Individuals::getValues();
    foreach ($acc->managing_persons as $pid){
        if (isset($p[$pid])){
            $person .= CHtml::link($p[$pid], $this->createUrl('/legal/individuals/view/', array('id' => $pid)));
        } else {
            $person .= $pid;
        }
        $person .= '<br/>';
    }

    $cur = Currencies::getValues();
    $cur = (isset($cur[$acc->cur])) ? $cur[$acc->cur] : 'Не указано';

	$this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => $acc,
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
