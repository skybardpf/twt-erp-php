<?php
/**
 * Просмотр корзины акционирования.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Cart_corporatizationController $this
 * @var string $cur_tab
 * @var string $scheme
 */
?>
<h2>Корзина акционирования</h2>
<div class="yur-tabs">
    <?php
    $this->widget('bootstrap.widgets.TbMenu', array(
        'type' => 'tabs', // '', 'tabs', 'pills' (or 'list')
        'stacked' => false, // whether this is a stacked menu
        'items' => array(
            array(
                'label' => 'Организации',
                'url' => $this->createUrl('index', array(
                    'type' => MTypeOrganization::ORGANIZATION,
                    'scheme' => 'direct'
                )),
                'active' => ($cur_tab == 'organization')
            ),
            array(
                'label' => 'Контрагенты',
                'url' => $this->createUrl('index', array(
                    'type' => MTypeOrganization::CONTRACTOR,
                    'scheme' => 'direct'
                )),
                'active' => ($cur_tab == 'contractor')
            ),
        )
    ));
    ?>
</div>
<div class="yur-content">
    <?php
    echo CHtml::label('Организация', 'organization_id');
    echo CHtml::dropDownList('organization_id', '', array());
    echo CHtml::label('Физическое лицо', 'individual_id');
    echo CHtml::dropDownList('individual_id', '', array());
    ?>


    <div class="yur-tabs">
        <?php
        $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'tabs', // '', 'tabs', 'pills' (or 'list')
            'stacked' => false, // whether this is a stacked menu
            'items' => array(
                array(
                    'label' => 'Прямая схема',
                    'url' => $this->createUrl('index', array(
                        'type' => MTypeOrganization::ORGANIZATION,
                        'scheme' => 'direct'
                    )),
                    'active' => ($scheme == 'direct')
                ),
                array(
                    'label' => 'Косвенная схема',
                    'url' => $this->createUrl('index', array(
                        'type' => MTypeOrganization::CONTRACTOR,
                        'scheme' => 'indirect'
                    )),
                    'active' => ($scheme == 'indirect')
                ),
            )
        ));
        ?>
    </div>
    <div class="yur-content">
        <?= $content; ?>
    </div>
</div>