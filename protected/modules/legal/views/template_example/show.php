<?php
/** @var $this Template_exampleController */
?>
<h1>ООО "Romashka"</h1>
<div class="yur-tabs">
    <?php $this->widget('bootstrap.widgets.TbMenu', array(
        'type'=>'tabs', // '', 'tabs', 'pills' (or 'list')
        'stacked'=>false, // whether this is a stacked menu
        'items'=>array(
            array('label'=>'Информация', 'url'=>$this->createUrl('show', array('id' => 1)), 'active'=> $this->cur_tab == 'index' ),
            array('label'=>'Документы', 'url'=>$this->createUrl('documents', array('id' => 1)), 'active'=> $this->cur_tab == 'documents'),
            array('label'=>'Расчётные счета', 'url'=>$this->createUrl('settlements', array('id' => 1)), 'active'=> $this->cur_tab == 'settlements'),
            array(
                'label'=>'Заинтересованные лица и бенефициары',
                'url'=>$this->createUrl('benefits', array('id' => 1)),
                'active'=> $this->cur_tab == 'benefits',
                'itemOptions' => array('class'=>'narrow')
            ),
            array(
                'label'=>'Календарь событий',
                'url'=>$this->createUrl('my_events', array('id' => 1)),
                'active'=> $this->cur_tab == 'my_events',
                'itemOptions' => array('class'=>'narrow narrower')
            ),
            array('label'=>'Договоры', 'url'=>$this->createUrl('contracts', array('id' => 1)), 'active'=> $this->cur_tab == 'contract'),
        ),
    )); ?>
</div>
<div class="yur-content">

	<?=$tab_content?>

</div>
<script type="text/javascript" src="/static/js/common.js"></script>
<!--$this->renderPartial('tabs_with_content', array('yur_content' => $this->renderPartial('info', array(''))))-->