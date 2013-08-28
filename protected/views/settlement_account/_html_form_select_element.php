<?php
/**
 * Банковские счета -> Модальная форма для добавления физ. лиц, управляющих счетом.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Settlement_accountController $this
 * @var array $data
 */

/**
 * @var TbActiveForm $form
 */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'menu-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
));
echo CHtml::dropDownList('select-element', '', $data);

$this->endWidget();