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

Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/cart_corporatization/index.js');
?>
<script>
    <?= 'window.orgType="'.$org_type.'";'; ?>
    <?= 'window.scheme="'.$scheme.'";'; ?>
</script>
<h2>Корзина акционирования</h2>
<div class="yur-tabs">
    <?php
    $this->widget('bootstrap.widgets.TbMenu', array(
        'type' => TbMenu::TYPE_TABS,
        'stacked' => false,
        'items' => array(
            array(
                'label' => 'Организации',
                'url' => $this->createUrl('direct', array(
                    'type' => MTypeOrganization::ORGANIZATION,
                )),
                'active' => ($cur_tab == 'organization')
            ),
            array(
                'label' => 'Контрагенты',
                'url' => $this->createUrl('direct', array(
                    'type' => MTypeOrganization::CONTRACTOR,
                )),
                'active' => ($cur_tab == 'contractor'),
            ),
        )
    ));
    ?>
</div>
<div class="yur-content">
    <?php
    if (!empty($organization_id) && !empty($individuals)){
        $options = array();
        if ($scheme == 'indirect'){
            $tab_indirect = array(
                'label' => 'Косвенная схема',
                'url' => $this->createUrl('indirect', array(
                    'type' => $org_type,
                    'oid' => $organization_id,
                    'iid' => $individual_id,
                )),
                'active' => ($scheme == 'indirect'),
            );
        } else {
            $tab_indirect = array(
                'label' => 'Косвенная схема',
                'url' => '',
                'active' => false,
                'disabled' => true
            );
        }

    } else {
        $options = array('disabled' => true);
        $tab_indirect = array(
            'label' => 'Косвенная схема',
            'url' => '',
            'active' => ($scheme == 'indirect'),
            'disabled' => true
        );
    }
    $org_name = ($org_type === MTypeOrganization::ORGANIZATION) ? 'Организации' : 'Контрагенты';
    $organizations[''] = '--- Все ---';
    $individuals[''] = '--- Все ---';

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
                    'url' => $this->createUrl('direct', array(
                        'type' => $org_type,
                        'oid' => $organization_id,
                    )),
                    'active' => ($scheme == 'direct')
                ),
                $tab_indirect,
            )
        ));
        ?>
    </div>
    <div class="yur-content">
        <?= $content; ?>
    </div>
</div>
