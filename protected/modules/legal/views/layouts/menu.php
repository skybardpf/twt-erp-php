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
<div class="well" style="padding: 8px 0;">
<?php
    if (in_array($_SERVER['HTTP_HOST'], array('twt-erp.twtconsult.ru', 'twt-erp.artektiv.ru', 'twt-erp.skybardpf.devel'))) {
        $menus = array(
            array(
                'label' => 'Физические лица',
                'url' => $this->createUrl('individuals/'),
                'active' => ($this->menu_current == 'individuals')
            ),
        );
    } else {
        $menus = array(
            array(
                'label' => 'Организации',
                'url' => $this->createUrl('organization/'),
                'active' => ($this->menu_current == 'legal')
            ),
            array(
                'label' => 'Контрагенты',
                'url' => $this->createUrl('contractor/'),
                'active' => ($this->menu_current == 'contractors')
            ),
            array(
                'label' => 'Календарь событий',
                'url'   => $this->createUrl('my_events/'),
                'active'=> ($this->menu_current == 'my_events')
            ),
            array(
                'label' => 'Физические лица',
                'url' => $this->createUrl('individuals/'),
                'active'=> ($this->menu_current == 'individuals')
            ),

            array(
                'label' => 'Банковские счета',
                'url'   => $this->createUrl('settlement_accounts/'),
                'active'=> ($this->menu_current == 'settlements')
            ),
            array(
                'label' => 'Корзина акционирования',
                'url'   => $this->createUrl('corporatization_basket/'),
                'active'=> ($this->menu_current == 'corporatization_basket')
            ),
            array(
                'label' => 'Виды договоров',
                'url'   => $this->createUrl('#'),
                'active'=> ($this->menu_current == 'contract_types')
            ),
            array(
                'label' => 'Библиотека шаблонов',
                'url'   => $this->createUrl('#'),
                'active'=> ($this->menu_current == 'template_library')
            ),
        );
    }

    $this->widget('bootstrap.widgets.TbMenu', array(
        'type' => 'list', // '', 'tabs', 'pills' (or 'list')
        'items' => $menus,
    ));
?>
</div>