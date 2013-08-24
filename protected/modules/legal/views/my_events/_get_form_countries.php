<?php
    /**
     * Выбор страны, прикрепленной к событию (мероприятию).
     *
     * @author Skibardin A.A. <webprofi1983@gmail.com>
     *
     * @var $this       My_eventsController
     * @var $data       array
     * @var $form       TbActiveForm
     */

    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
        'id' => 'menu-form-countries',
        'enableAjaxValidation' => false,
        'type' => 'horizontal',
    ));

    echo CHtml::dropDownList('select_countries', '', $data);

    $this->endWidget();