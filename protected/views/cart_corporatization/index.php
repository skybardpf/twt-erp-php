<?php
/**
 * Просмотр корзины акционирования.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Cart_corporatizationController $this
 * @var string $cur_tab
 * @var string $scheme
 * @var string $org_type
 *
 * @var array $organizations
 * @var array $individuals
 * @var string $organization_id
 * @var string $individual_id
 */

Yii::import('bootstrap.widgets.TbMenu');
$options = (empty($organization_id) || empty($individuals)) ? array('disabled' => true) : array();
?>
<h2>Корзина акционирования</h2>
<div class="yur-tabs">
    <?php
    $this->widget('bootstrap.widgets.TbMenu', array(
        'type' => TbMenu::TYPE_PILLS,
        'stacked' => false,
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
                'active' => ($cur_tab == 'contractor'),
            ),
        )
    ));
    ?>
</div>
<!--<div class="yur-content">-->
    <?php
    $org_name = ($org_type === MTypeOrganization::ORGANIZATION) ? 'Организации' : 'Контрагенты';

    $organizations[''] = 'Все';
    $individuals[''] = 'Все';

    echo CHtml::label($org_name, 'organization_id');
    echo CHtml::dropDownList('organization_id', $organization_id, $organizations);
    echo CHtml::label('Физическое лицо', 'individual_id');
    echo CHtml::dropDownList('individual_id', $individual_id, $individuals, $options);
    ?>


    <div class="yur-tabs">
        <?php
        $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => TbMenu::TYPE_TABS,
            'stacked' => false,
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
                    'active' => ($scheme == 'indirect'),
                    'itemOptions' => array(
                        'disabled' => true
                    )
                ),
            )
        ));
        ?>
    </div>
    <div class="yur-content">
        <?= $content; ?>
    </div>
<!--</div>-->