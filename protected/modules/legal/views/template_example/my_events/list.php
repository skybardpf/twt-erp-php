<?php
/** @var $this Template_exampleController */
?>
<h2>Ближайшие события</h2>
<div>
    <a href="#">Ближайшие 10</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="#">На год вперёд</a>
</div>
<br>
<table class="items table table-striped table-bordered table-condensed">
    <tr>
        <th>Название</th>
        <th>Дата следующего наступления</th>
        <th> </th>
    </tr>
    <tr>
        <td><a href="/legal/template_example/event_show/id/1">Сдача квартальной отчётности</a></td>
        <td>15.07.2013</td>
        <td>
            <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'success', 'label' => 'Пометить как выполненное')); ?>
        </td>
    </tr>
    <tr>
        <td><a href="/legal/template_example/event_show/id/1">Собрание акционеров</a></td>
        <td>21.10.2013</td>
        <td>
            <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'success', 'label' => 'Пометить как выполненное')); ?>
        </td>
    </tr>
    <tr>
        <td><a href="/legal/template_example/event_show/id/1">Сдача годовой отчётности</a></td>
        <td>15.01.2013</td>
        <td>
            <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'success', 'label' => 'Пометить как выполненное')); ?>
        </td>
    </tr>
</table>