<?php
/**
 * Просмотр Юр.Лица
 *
 * User: Forgon
 * Date: 23.04.2013 от рождества Христова
 *
 * @var $this My_OrganizationsController
 * @var $model Organizations
 */
?>
<h1><?=$model->full_name?></h1>
<div class="yur-tabs">
    <?php $this->widget('bootstrap.widgets.TbMenu', array(
        'type'=>'tabs', // '', 'tabs', 'pills' (or 'list')
        'stacked'=>false, // whether this is a stacked menu
        'items'=>array(
            array('label'=>'Информация', 'url'=>$this->createUrl('show', array('id' => $model->id)), 'active'=> $this->cur_tab == 'info' ),
            array('label'=>'Документы', 'url'=>$this->createUrl('documents', array('id' => $model->id)), 'active'=> $this->cur_tab == 'documents'),
            array('label'=>'Банковские счета', 'url'=>$this->createUrl('settlements', array('id' => $model->id)), 'active'=> $this->cur_tab == 'settlements'),
            array(
                'label'=>'Заинтересованные лица',
                'url'=>$this->createUrl('benefits', array('id' => $model->id)),
                'active'=> $this->cur_tab == 'benefits',
                'itemOptions' => array('class'=>'narrow')
            ),
            array(
                'label'=>'Календарь событий',
                'url'=>$this->createUrl('my_events', array('id' => $model->id)),
                'active'=> $this->cur_tab == 'my_events',
                'itemOptions' => array('class'=>'narrow narrower')
            ),
            array('label'=>'Договоры', 'url'=>$this->createUrl('contracts', array('id' => $model->id)), 'active'=> $this->cur_tab == 'contracts'),
            /*array('label'=>'Расчётные счета', 'url'=>$this->createUrl('settlements', array('id' => $model->id)), 'active'=> $this->cur_tab == 'settlements'),
            array(
                'label'=>'Заинтересованные лица и бенефициары',
                'url'=>$this->createUrl('benefits', array('id' => $model->id)),
                'active'=> $this->cur_tab == 'benefits',
                'itemOptions' => array('class'=>'narrow')
            ),
            array(
                'label'=>'Календарь событий',
                'url'=>$this->createUrl('my_events', array('id' => $model->id)),
                'active'=> $this->cur_tab == 'my_events',
                'itemOptions' => array('class'=>'narrow narrower')
            ),
            array('label'=>'Договоры', 'url'=>$this->createUrl('contracts', array('id' => $model->id)), 'active'=> $this->cur_tab == 'contract'),*/
        ),
    )); ?>
</div>
<div class="yur-content">
	<?=$tab_content?>
</div>