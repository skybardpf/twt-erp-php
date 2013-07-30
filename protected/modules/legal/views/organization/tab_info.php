<?php
/**
 *  Вкладка информация о Юр.Лице
 *
 *  @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 *  @var OrganizationController     $this
 *  @var Organization               $model
 */

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'link',
        'type' => 'success',
        'label' => 'Редактировать',
        'url' => $this->createUrl("edit", array('id' => $model->primaryKey))
    ));
    echo "&nbsp;";
    if (!$model->deleted) {
        Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/delete_item.js');
        $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'buttonType'    => 'submit',
                'type'          => 'danger',
                'label'         => 'Удалить',
                'htmlOptions'   => array(
                    'data-question'     => 'Вы уверены, что хотите удалить данную организацию?',
                    'data-title'        => 'Удаление организации',
                    'data-url'          => $this->createUrl('delete', array('id' => $model->primaryKey)),
                    'data-redirect_url' => $this->createUrl('index'),
                    'data-delete_item_element' => '1'
                )
            )
        );
    }
?>
<br/><br/>
<?php
    $countries = Countries::getValues();
    $types = ContractorTypesActivities::getValues();

    $this->widget('bootstrap.widgets.TbDetailView', array(
        'data' => $model,
        'attributes' => array(
            array(
                'name' => 'country',
                'label' => 'Страна',
                'value' => (isset($countries[$model->country]) ? $countries[$model->country] : '---')

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
                'label' => 'Дата государственной регистрации'
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
                'name' => 'ogrn',
                'label' => 'ОГРН'
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
    ));
?>