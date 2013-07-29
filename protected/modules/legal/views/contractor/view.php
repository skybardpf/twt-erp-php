<?php
/**
 * Просмотр информации об контрагенте.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var $this   ContractorController
 * @var $model  Contractor
 */
?>

<?php
    echo '<h2>'.$model->name.'</h2>';

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
                'data-question'     => 'Вы уверены, что хотите удалить контрагента?',
                'data-title'        => 'Удаление контрагента',
                'data-url'          => $this->createUrl('delete', array('id' => $model->primaryKey)),
                'data-redirect_url' => $this->createUrl('index'),
                'data-delete_item_element' => '1'
            )
        ));
    }
?>
<br/><br/>
<?php
    $countries = Countries::getValues();
    $types = ContractorTypesActivities::getValues();
    $this->widget('bootstrap.widgets.TbDetailView',
        array(
            'data'=> $model,
            'attributes' => array(
                array(
                    'name' => 'XXX',
                    'label' => 'Группа'
                ),
                array(
                    'name' => 'country',
                    'label' => 'Страна',
                    'value' => isset($countries[$model->country]) ? $countries[$model->country] : '—'
                ),
                array(
                    'name' => 'name',
                    'label' => 'Наименование'
                ),
                array(
                    'name' => 'full_name',
                    'label' => 'Полное наименование'
                ),
                array(
                    'name' => 'sert_date',
                    'label' => 'Дата гос. регистрации'
                ),
                array(
                    'name' => 'inn',
                    'label' => 'ИНН'
                ),
                array(
                    'name' => 'kpp',
                    'label' => 'КПП'
                ),
                array(
                    'name' => 'profile',
                    'label' => 'Основной вид деятельности',
                    'value' => (isset($types[$model->profile]) ? $types[$model->profile] : '')
                ),
                array(
                    'name' => 'yur_address',
                    'label' => 'Юридический адрес'
                ),
                array(
                    'name' => 'fact_address',
                    'label' => 'Фактический адрес'
                ),
                array(
                    'name' => 'email',
                    'label' => 'E-mail'
                ),
                array(
                    'name' => 'phone',
                    'label' => 'Телефон'
                ),
                array(
                    'name' => 'fax',
                    'label' => 'Факс'
                ),
            )
       )
    );
?>