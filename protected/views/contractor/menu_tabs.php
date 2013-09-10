<?php
/**
 * Просмотр информации об контрагенте. Иформация разбита по вкладкам.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Controller    $this
 * @var Contractor    $organization
 * @var string        $content
 * @var string        $cur_tab
 */
?>

<?php
    echo '<h2>'.$organization->name.'</h2>';
?>
<div class="yur-tabs">
<?php
    $this->widget('bootstrap.widgets.TbMenu', array(
        'type' => 'tabs',
        'stacked' => false,
        'items' => array(
            array(
                'label' => 'Информация',
                'url'   => $this->createUrl('contractor/view', array('id' => $organization->primaryKey)),
                'active'=> ($cur_tab == 'info')
            ),
            array(
                'label' => 'Доверенности',
                'url'   => $this->createUrl('power_attorney_contractor/list', array('cid' => $organization->primaryKey)),
                'active'=> ($cur_tab == 'power_attorney')
            ),
            array(
                'label' => 'Бенефициары',
                'url' => $this->createUrl('interested_person_beneficiary/index', array(
                    'org_id' => $organization->primaryKey,
                    'org_type' => $organization->type,
                )),
                'active' => ($cur_tab == 'beneficiary')
            ),
        ),
    ));
?>
</div>

<div class="yur-content">
    <?= $content; ?>
</div>