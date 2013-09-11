<?php
/**
 * Просмотр номинального акционера.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Interested_person_shareholderController $this
 * @var InterestedPersonShareholder $model
 * @var Organization $organization
 */
?>
<h2><?= CHtml::encode($model->person_name); ?></h2>
<?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'link',
        'type'       => 'success',
        'label'      => 'Редактировать',
        'url'        => $this->createUrl(
            "edit",
            array(
                'id' => $model->primaryKey,
                'type_lico' => $model->type_lico,
                'id_yur' => $model->id_yur,
                'type_yur' => $model->type_yur,
                'date' => $model->date,
                'number_stake' => $model->number_stake,
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
                'data-question' => 'Вы уверены, что хотите удалить номинального акционера?',
                'data-title' => 'Удаление номинального акционера',
                'data-url' => $this->createUrl(
                    'delete',
                    array(
                        'id' => $model->primaryKey,
                        'type_lico' => $model->type_lico,
                        'id_yur' => $model->id_yur,
                        'type_yur' => $model->type_yur,
                        'date' => $model->date,
                        'number_stake' => $model->number_stake,
                    )
                ),
                'data-redirect_url' => $this->createUrl(
                    'interested_person/index',
                    array(
                        'org_id' => $organization->primaryKey,
                        'type' => $model->pageTypePerson
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
    $currency = Currency::model()->listNames($model->forceCached);
    $model->deleted = $model->deleted ? "Недействителен" : "Действителен";

	$this->widget('bootstrap.widgets.TbDetailView', array(
		'data' => $model,
		'attributes' => array(
			array(
                'name' => 'date',
                'label' => 'Дата вступления в должность'
            ),
			array(
                'name' => 'deleted',
                'label' => 'Текущее состояние',
            ),
			array(
                'name' => 'value_stake',
                'label' => 'Величина пакета акций'
            ),
			array(
                'name' => 'date_issue_stake',
                'label' => 'Дата выпуска пакета акций'
            ),
            array(
                'name' => 'number_stake',
                'label' => 'Номер пакета акций'
            ),
            array(
                'name' => 'type_stake',
                'label' => 'Вид акций'
            ),
            array(
                'name' => 'count_stake',
                'label' => 'Кол-во акций'
            ),
            array(
                'name' => 'nominal_stake',
                'label' => 'Номинальная стоимость пакета акций'
            ),
            array(
                'name' => 'currency',
                'label' => 'Валюта номинала акций',
                'value' => (isset($currency[$model->currency])) ? $currency[$model->currency] : '---'
            ),
            array(
                'name' => 'description',
                'label' => 'Дополнительные сведения'
            ),
		)
	));
?>
</div>
