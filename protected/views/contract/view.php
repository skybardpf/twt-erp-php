<?php
/**
 * Просмотр информации о договоре.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var ContractController $this
 * @var Contract            $model
 * @var Organization       $organization
 */
?>

<?php
    echo '<h2>Договор</h2>';

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'link',
        'type' => 'success',
        'label' => 'Редактировать',
        'url' => $this->createUrl("edit", array('id' => $model->primaryKey))
    ));

    if (!$model->deleted) {
        echo "&nbsp;";
        Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/delete_item.js');

        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'    => 'submit',
            'type'          => 'danger',
            'label'         => 'Удалить',
            'htmlOptions'   => array(
                'data-question'     => 'Вы уверены, что хотите удалить договор?',
                'data-title'        => 'Удаление договора',
                'data-url'          => $this->createUrl('delete', array('id' => $model->primaryKey)),
                'data-redirect_url' => $this->createUrl('list', array('id' => $model->id_yur)),
                'data-delete_item_element' => '1'
            )
        ));
    }
?>
<br/><br/>
<?php
    $currency = Currency::model()->listNames($this->getForceCached());
    $persons = Individual::model()->listNames($this->getForceCached());
    $prolongation = Contract::getProlongationTypes();
    $contractors = Contractor::model()->getListNames($this->getForceCached());
    $court_locations = CourtLocation::model()->listNames($this->getForceCached());
    $contract_place = ContractPlace::model()->listNames($this->getForceCached());
    $contractor = (isset($contractors[$model->contractor_id]) ? $contractors[$model->contractor_id] : '---');

    $side_data = array();
    $side_attr['role'] = array(
        'name' => 'role',
        'label' => 'Роль'
    );
    foreach($model->organization_signatories as $id){
        $pid = 'person_'.$id;
        $side_data[$pid] = (isset($persons[$id])
            ? CHtml::link(
                $persons[$id],
                $this->createUrl('Individual/view', array('id' => $id))
            )
            : '---'
        );
        $side_attr[$pid] = array(
            'name' => $pid,
            'label' => 'Подписант',
            'type' => 'raw'
        );
    }
    $side_data = array_merge(array('role' => $model->role), $side_data);
    $view_side_1 = $this->widget('bootstrap.widgets.TbDetailView',
        array(
            'data' => $side_data,
            'attributes' => $side_attr
        ),
        true
    );

    $side_data = array();
    $side_attr = array();
    foreach($model->contractor_signatories as $id){
        $pid = 'person_'.$id;
        $side_data[$pid] = (isset($persons[$id])
            ? CHtml::link(
                $persons[$id],
                $this->createUrl('Individual/view', array('id' => $id))
            )
            : '---'
        );
        $side_attr[$pid] = array(
            'name' => $pid,
            'label' => 'Подписант',
            'type' => 'raw'
        );
    }
    $view_side_2 = $this->widget('bootstrap.widgets.TbDetailView',
        array(
            'data' => $side_data,
            'attributes' => $side_attr
        ),
        true
    );

    $this->widget('bootstrap.widgets.TbDetailView',
        array(
            'data' => $model,
            'attributes' => array(
                array(
                    'name' => 'typ_doc',
                    'label' => 'Вид договора'
                ),
                array(
                    'name' => 'contractor_id',
                    'label' => 'Контрагент',
                    'value' => $contractor
                ),
                array(
                    'name' => 'name',
                    'label' => 'Наименование'
                ),
                array(
                    'name' => 'number',
                    'label' => 'Номер'
                ),
                array(
                    'name' => 'date',
                    'label' => 'Дата заключения'
                ),
                array(
                    'name' => 'expire',
                    'label' => 'Действителен до'
                ),
                array(
                    'name' => 'place_contract_id',
                    'label' => 'Место заключения',
                    'value' => (isset($contract_place[$model->place_contract_id])) ? $contract_place[$model->place_contract_id] : '---'
                ),
                array(
                    'name' => 'prolongation_type',
                    'label' => 'Тип пролонгации',
                    'value' => (isset($prolongation[$model->prolongation_type])) ? $prolongation[$model->prolongation_type] : '---'
                ),
                array(
                    'name' => 'sum',
                    'label' => 'Сумма договора',
                    'value' => $model->sum . ' ' . $currency[$model->currency]
                ),
                array(
                    'name' => 'everymonth_summ',
                    'label' => 'Сумма ежемесячного платежа'
                ),
                array(
                    'name' => 'responsible',
                    'label' => 'Ответственный по договору',
                    'value' => (isset($persons[$model->responsible]) ? $persons[$model->responsible] : '---')
                ),
                array(
                    'name' => 'id_yur',
                    'label' => 'Сторона 1 ('.CHtml::encode($organization->name).')',
                    'type' => 'raw',
                    'value' => $view_side_1
                ),
                array(
                    'name' => 'contractor_id',
                    'label' => 'Сторона 2 ('.CHtml::encode($contractor).')',
                    'type' => 'raw',
                    'value' => $view_side_2
                ),
                array(
                    'name' => 'date_infomation',
                    'label' => 'Уведомлять об окончании действия договора'
                ),
                array(
                    'name' => 'place_court_id',
                    'label' => 'Место судебной инстанции',
                    'value' => (isset($court_locations[$model->place_court_id])) ? $court_locations[$model->place_court_id] : '---'
                ),
            )
       )
    );

//    echo 'Скачать электронную версию: '.CHtml::link('сгенерированную системой').' или '.CHtml::link('пользовательскую');
//    echo '<br/>'.CHtml::link('Скачать скан');
?>