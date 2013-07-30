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
    $this->widget('bootstrap.widgets.TbMenu', array(
        'type'=>'list', // '', 'tabs', 'pills' (or 'list')
        'items'=>array(
            array(
                'label' => 'Организации',
                'url' => '/legal/organization/',
                'active' => ($this->menu_current == 'legal')
            ),
            array(
                'label' => 'Контрагенты',
                'url' => '/legal/contractor/',
                'active' => ($this->menu_current == 'contractors')
            ),
            array(
                'label' => 'Календарь событий',
                'url'   => '/legal/my_events/',
                'active'=> ($this->menu_current == 'my_events')
            ),
            array(
                'label' => 'Физические лица',
                'url'=>'/legal/individuals/',
                'active'=> ($this->menu_current == 'individuals')
            ),

            array(
                'label' => 'Банковские счета',
                'url'   => '/legal/settlement_accounts/',
                'active'=> ($this->menu_current == 'settlements')
            ),
            array(
                'label' => 'Корзина акционирования',
                'url'   => '/legal/corporatization_basket/',
                'active'=> ($this->menu_current == 'corporatization_basket')
            ),
            array(
                'label' => 'Виды договоров',
                'url'   => '#',
                'active'=> ($this->menu_current == 'contract_types')
            ),
            array(
                'label' => 'Библиотека шаблонов',
                'url'   => '#',
                'active'=> ($this->menu_current == 'template_library')
            ),
        ),
    ));
?>
</div>