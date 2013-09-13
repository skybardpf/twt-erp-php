<div class="well" style="padding: 8px 0;">
<?php

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
            'label' => 'Физические лица',
            'url' => $this->createUrl('individual/'),
            'active'=> ($this->menu_current == 'individuals')
        ),
        array(
            'label' => 'Календарь событий',
            'url'   => $this->createUrl('my_events/'),
            'active'=> ($this->menu_current == 'my_events')
        ),
        array(
            'label' => 'Банковские счета',
            'url'   => $this->createUrl('settlement_account/'),
            'active'=> ($this->menu_current == 'settlements')
        ),
        array(
            'label' => 'Библиотека шаблонов',
            'url'   => $this->createUrl('template_library/'),
            'active'=> ($this->menu_current == 'template_library')
        ),
        array(
            'label' => 'Виды договоров',
            'url'   => $this->createUrl('contract_type/'),
            'active'=> ($this->menu_current == 'contract_types')
        ),
        array(
            'label' => 'Корзина акционирования',
            'url'   => $this->createUrl('cart_corporatization/'),
            'active'=> ($this->menu_current == 'cart_corporatization')
        ),
    );
    $this->widget('bootstrap.widgets.TbMenu', array(
        'type' => 'list', // '', 'tabs', 'pills' (or 'list')
        'items' => $menus,
    ));
?>
</div>