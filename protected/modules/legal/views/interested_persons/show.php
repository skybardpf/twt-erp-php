<?php
/**
 * Просмотр заитересованного лица.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var $this           Interested_personsController
 * @var $model          InterestedPerson | Beneficiary
 * @var $organization   Organizations
 */

?>

<h2><?= $model->lico; ?></h2>

<?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'link',
        'type'       => 'success',
        'label'      => 'Редактировать',
        'url'        => $this->createUrl(
            "edit",
            array(
                'id' => $model->primaryKey,
                'id_yur' => $model->id_yur,
                'role' => $model->role,
                'numPack' => $model->numPack,
            )
        )
    ));

    if (!$model->deleted) {
        echo "&nbsp;";
        Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/delete_item.js');

        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'    => 'submit',
            'type'          => 'danger',
            'label'         => 'Удалить',
            'htmlOptions'   => array(
                'data-question' => 'Вы уверены, что хотите удалить заинтересованное лицо?',
                'data-title' => 'Удаление заинтересованного лица',
                'data-url' => $this->createUrl(
                    'delete',
                    array(
                        'id' => $model->primaryKey,
                        'id_yur' => $model->id_yur,
                        'role' => $model->role,
                        'numPack' => $model->numPack,
                    )
                ),
                'data-redirect_url' => $this->createUrl(
                    'interested_persons/index',
                    array(
                        'org_id' => $organization->primaryKey
                    )
                ),
                'data-delete_item_element' => '1'
            )
        ));
    }
?>
<br/><br/>
<div>
<?php
    $model->deleted = $model->deleted ? "Недействителен" : "Действителен";
	$this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => $model,
		'attributes' => array(
			//array('name' => 'id',           'label' => 'Лицо', type),
//			array('name' => 'id_yur',         'label' => 'Юр.Лицо', 'type' => 'raw', 'value' => ($element->id_yur && isset(LegalEntities::$values[$element->id_yur])) ? LegalEntities::$values[$element->id_yur] : "Не указано"),
			array('name' => 'role',           'label' => 'Роль'),
			array('name' => 'date',           'label' => 'Дата вступления в должность'),
			array(
                'name' => 'deleted',
                'label' => 'Текущее состояние',
            ),
			array('name' => 'percent',        'label' => 'Величина пакета акций'),
			array('name' => 'dateIssue',      'label' => 'Дата выпуска пакета акций'),
            array('name' => 'cost',           'label' => 'Номинальная стоимость пакета акций'),
            array('name' => 'numPack',        'label' => 'Номер пакета акций'),
            array('name' => 'typeStock',      'label' => 'Вид акций'),
            array('name' => 'quantStock',     'label' => 'Кол-во акций'),

			/*array('name' => 'eng_name',     'label' => 'Английское наименование'),
			array('name' => 'country',      'label' => 'Страна юрисдикции', 'type' => 'raw', 'value' => $element->country ? Countries::$values[$element->country] : 'Не указана'),


			 <!--'id'            => 'Лицо',
	    'role'          => 'Роль',
	    'add_info'      => 'Дополнительные сведения',
	    'cost'          => 'Номинальная стоимость пакета акций',
	    'percent'       => 'Величина пакета акций',
	    'vid'           => 'Вид лица', // (выбор из справочника юр. лиц или физ. лиц, обязательное); Физические лица
	    'cur'           => 'Валюта номинальной стоимости',
	    'deleted'       => 'Удален',
	    'id_yur'        => 'Юр.Лицо'-->
			*/
		)
	));
?>
</div>
