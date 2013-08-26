<?php
    /**
     * Выбор организации, прикрепленной к событию (мероприятию).
     *
     * @author Skibardin A.A. <webprofi1983@gmail.com>
     *
     * @var My_eventsController $this
     * @var array               $data
     * @var string              $type
     * @var TbActiveForm        $form
     */

    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
        'id'=>'form-select-element',
        'enableAjaxValidation' => false,
        'type' => 'horizontal',
        'htmlOptions' => array(
            'data-type' => $type
        )
    ));
    echo CHtml::dropDownList('select-element', '', $data);
    $this->endWidget();