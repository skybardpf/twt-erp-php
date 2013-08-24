<?php
/**
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var SoapModel   $model
 * @var string      $attribute
 * @var string      $attribute_files
 * @var array       $data
 * @var string      $grid_id
 * @var string      $accept_ext
 */
?>
<div class="control-group">
    <?= CHtml::activeLabelEx($model, $attribute, array('class' => 'control-label')); ?>
    <div class="controls">
        <?php
        $this->widget('bootstrap.widgets.TbGridView',
            array(
                'id' => $grid_id,
                'type' => 'striped bordered condensed',
                'dataProvider' => new CArrayDataProvider($data),
                'template' => "{items}",
                'columns' => array(
                    array(
                        'name' => 'filename',
                        'header' => 'Название',
                        'type' => 'raw',
                        'htmlOptions' => array(
                            'style' => 'width: 90%',
                        )
                    ),
                    array(
                        'name' => 'delete',
                        'header' => '',
                        'type' => 'raw'
                    ),
                )
            )
        );
        $this->widget('CMultiFileUpload', array(
            'name' => $attribute_files,
//            'accept' => 'jpeg|jpg|gif|png', // useful for verifying files
            'accept' => $accept_ext,
            'duplicate' => 'Файл с таким именем уже выбран!',
            'denied' => 'Неправильный тип файла',
            'htmlOptions' => array(
                'multiple' => 'multiple',
            ),
        ));
        echo CHtml::error($model, $attribute);
        ?>
    </div>
</div>