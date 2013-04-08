<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Мишенько
 * Date: 08.04.13
 * Time: 10:57
 * To change this template use File | Settings | File Templates.
 */
/*@var $this Controller */
$this->beginContent('//layouts/template_main');
?>
<div class="row">
    <div class="span3">
        <?php $this->renderPartial('/menu/menu'); ?>
    </div>
    <div class="span9">
        <?php echo $content; ?>
    </div>
</div>
<?php $this->endContent(); ?>