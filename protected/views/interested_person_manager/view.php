<?php
/**
 * Просмотр менеджера.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Interested_person_shareholderController $this
 * @var InterestedPersonManager $model
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
                'data-question' => 'Вы уверены, что хотите удалить менеджера?',
                'data-title' => 'Удаление менеджера',
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
                'name' => 'job_title',
                'label' => 'Наименование должности',
            ),
            array(
                'name' => 'description',
                'label' => 'Дополнительные сведения'
            ),
		)
	));
?>
</div>
