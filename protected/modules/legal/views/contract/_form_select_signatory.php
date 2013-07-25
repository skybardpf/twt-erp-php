<?php
    /**
     * Возращает html форму для выбора подписанта договора.
     *
     * @author Skibardin A.A. <skybardpf@artektiv.ru>
     *
     * @var ContractController  $this
     * @var array               $data
     * @var string              $type
     * @var TbActiveForm        $form
     */

    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
        'id' => 'form-select-signatory',
        'enableAjaxValidation' => false,
        'type' => 'horizontal',
        'htmlOptions' => array(
            'data-type' => $type
        )
    ));
    echo CHtml::dropDownList('select_signatory', '', $data);
    $this->endWidget();