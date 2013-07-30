<?php
/**
 * @var Controller $this
 */

if (in_array($_SERVER['HTTP_HOST'], array('twt-erp.twtconsult.ru', 'twt-erp.artektiv.ru'))) {
    $brandUrl = $this->createUrl('organization/');
} else {
    $brandUrl = $this->createUrl('individuals/');
}
$this->widget('bootstrap.widgets.TbNavbar', array(
    'brand'    => 'TWT — ERP',
    'brandUrl' => $brandUrl,
    'collapse' => true
));
