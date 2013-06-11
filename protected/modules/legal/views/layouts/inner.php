<?php
/**
 * User: Мишенько
 * Date: 08.04.13
 * Time: 10:57
 *
 * @var $this Controller
 */

$this->beginContent('/layouts/main');
?>
<div class="row">
    <div class="span3">
        <?php $this->renderPartial('/layouts/menu'); ?>
    </div>
    <div class="span9">
        <?php echo $content; ?>
    </div>
</div>
<?php $this->endContent(); ?>