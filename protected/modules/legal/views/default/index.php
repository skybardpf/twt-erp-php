<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);
?>
<form method="post">
<label>Название метода: <input type="text" name="method" value="<?=!empty($data['method'])?$data['method']:''?>"></label><br/>
<label>Аргументы: <textarea rows="10" cols="60" name="args"><?=!empty($data['args'])?$data['args']:''?></textarea></label><br/>
<input type="submit" value="Попробовать">
</form>