<?php
    /**
     * Выбор подписанта и доверености.
     *
     * @author Skibardin A.A. <skybardpf@artektiv.ru>
     *
     * @var ContractorController $this
     * @var array               $persons
     * @var array               $docs
     * @var TbActiveForm        $form
     */

    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
        'id'=>'form-select-element',
        'enableAjaxValidation' => false,
        'type' => 'horizontal',
    ));
//    echo CHtml::dropDownList('select-person', '', $persons);
    echo CHtml::dropDownList('select-doc', '', $docs);

    $this->endWidget();