<?php
/**
 * Вкладка информация о Юр.Лице
 *
 * User: Forgon
 * Date: 23.04.2013 от рождества Христова
 *
 * @var $this My_OrganizationsController
 * @var $model Organizations
 */

Countries::getValues();

	$this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'link', 'type'=>'success', 'label'=>'Редактировать', 'url' => Yii::app()->getController()->createUrl("add")));
	echo "&nbsp;";
	if (!$model->deleted)
		$this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type' => 'danger', 'label'=>'Удалить'));
?>
<br>
<br>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data'=> $model,
    'attributes'=>array(
        array('name'=>'country',        'label'=>'Страна',                  'value' => isset(Countries::$values[$model->country]) ? Countries::$values[$model->country] : '—'),
        array('name'=>'name',           'label'=>''),
        array('name'=>'full_name',      'label'=>'Полное наименование',     'value' => ($model->full_name ? $model->full_name : '—')),
        array('name'=>'inn',            'label'=>'ИНН',                     'value' => ($model->inn ? $model->inn : '—')),
        array('name'=>'kpp',            'label'=>'КПП',                     'value' => ($model->kpp ? $model->kpp : '—')),
        array('name'=>'ogrn',           'label'=>'ОГРН',                    'value' => ($model->ogrn ? $model->ogrn : '—')),
        array('name'=>'yur_address',    'label'=>'Юридический адрес',       'value' => ($model->yur_address ? $model->yur_address : '—')),
        array('name'=>'fact_address',   'label'=>'Фактический адрес',       'value' => ($model->fact_address ? $model->fact_address : '—')),

        // TODO узнать судьбу этих полей
        //array('name'=>'reg_nom',        'label'=>'Регистрационный номер'),
        //array('name'=>'sert_nom',       'label'=>'Номер сертификата',       'value' => ($model->sert_nom ? $model->sert_nom : '—')),
        //array('name'=>'profile',        'label'=>'Вид деятельности'),
        //eng_name
        //resident
        //type_no_res
        //sert_date
        //vat_nom

        //array('name'=>'comment',        'label'=>'Комментарий'),
    ),
)); ?>