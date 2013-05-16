<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Мишенько
 * Date: 08.04.13
 * Time: 10:58
 * To change this template use File | Settings | File Templates.
 */

/* @var $this Template_exampleController */
?>
<?//=$this->menu_current?>
<div class="well" style="padding: 8px 0;">
<?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'list', // '', 'tabs', 'pills' (or 'list')
    'items'=>array(
        array('label'=>'Мои юридические лица', 'url'=>'/legal/my_organizations/', 'active'=> ($this->menu_current == 'legal')),
        /*array('label'=>'Мои контрагенты', 'url'=>'/legal/template_example/contragents/', 'active'=> ($this->menu_current == 'contragents')),
        array('label'=>'Мои мероприятия', 'url'=>'/legal/template_example/events/', 'active'=> ($this->menu_current == 'events')),*/
        array('label'=>'Мои физические лица', 'url'=>'/legal/template_example/individuals/', 'active'=> ($this->menu_current == 'individuals'))
    ),
)); ?>
</div>