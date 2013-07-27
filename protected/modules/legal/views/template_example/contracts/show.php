<?php
/**
 * @var $this Template_exampleController
 */
?>

<div class="pull-right" style="margin-top: 15px;">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'link', 'type' => 'success', 'label' => 'Редактировать', 'url' => Yii::app()->getController()->createUrl("contract_add"))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'danger', 'label' => 'Удалить')); ?>
</div>
<h1>Договор</h1>
<br>
<br>
<table id="yw0" class="detail-view table table-striped table-condensed">
    <tr>
        <th>Характер договора</th>
        <td>Договор купли-продажи</td>
    </tr>
    <tr>
        <th>Контрагент</th>
        <td><a href = "/legal/template_example/show/id/1">ООО "Василёк"</a></td>
    </tr>
    <tr>
        <th>Наименование</th>
        <td>Договор</td>
    </tr>
    <tr>
        <th>Номер</th>
        <td>253</td>
    </tr>
    <tr>
        <th>Дата заключения</th>
        <td>01.03.2013</td>
    </tr>
    <tr>
        <th>Место заключения</th>
        <td>Москва</td>
    </tr>
    <tr>
        <th>Дата вступления в силу</th>
        <td>01.03.2013</td>
    </tr>
    <tr>
        <th>Действителен до</th>
        <td>01.03.2013</td>
    </tr>
    <tr>
        <th>Тип пролонгации</th>
        <td>автоматически</td>
    </tr>
    <tr>
        <th>Сумма договора</th>
        <td>1 000 000 RUR</td>
    </tr>
    <tr>
        <th>Сумма ежемесячного платежа</th>
        <td>—</td>
    </tr>
    <tr>
        <th>Ответственный по договору</th>
        <td><a href="/legal/template_example/person_show/id/1">Померанцев Павел Вячеславович</a></td>
    </tr>
    <tr>
        <th>Комментарий</th>
        <td></td>
    </tr>
    <tr>
        <th>Сторона 1 (ООО "Ромашка")</th>
        <td>
            <table id="yw0" class="detail-view table table-condensed">
                <tr>
                    <th>Роль</th>
                    <td>продавец</td>
                </tr>
                <tr>
                    <th>Подписанты</th>
                    <td><a href="/legal/template_example/person_show/id/2">Прошлецов Андрей Андреевич</a></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <th>Сторона 2 (ООО "Василёк")</th>
        <td>
            <table id="yw1" class="detail-view table table-condensed">
                <tr>
                    <th>Роль</th>
                    <td>покупатель</td>
                </tr>
                <tr>
                    <th>Подписанты</th>
                    <td><a href="/legal/template_example/person_show/id/2">Малхасян Геворк Рубенович</a></td>
                </tr>
            </table>
        </td>
    </tr>
</table>