<?php
	/**
	 * @var $this BankAccountEditWidget
	 * @var $bank_account CBankAccount
	 */
?>
<div class="form">
	<fieldset>
		<?=CHtml::activeLabelEx($bank_account, 'account_number', array('class' => 'control-label lefter'));?>
		<?=CHtml::activeTextField($bank_account, 'account_number' , array('id' => 'CBankAccount_account_number_field', 'class' => 'input-xlarge margin'));?>
		<?=CHtml::activeLabelEx($bank_account, 'bank', array('class' => 'control-label lefter'));?>
		<?=CHtml::activeTextField($bank_account, 'bank' , array('id' => 'CBankAccount_bank_field', 'class' => 'input-xlarge margin'));?>
		<?php if ($resident) { ?>
			<?=CHtml::activeLabelEx($bank_account, 'bik', array('class' => 'control-label lefter'));?>
			<?=CHtml::activeTextField($bank_account, 'bik' , array('id' => 'CBankAccount_bik_field', 'class' => 'input-xlarge margin'));?>
			<?=CHtml::activeLabelEx($bank_account, 'correspondent', array('class' => 'control-label lefter'));?>
			<?=CHtml::activeTextField($bank_account, 'correspondent' , array('id' => 'CBankAccount_correspondent_field', 'class' => 'input-xlarge margin'));?>
		<?php } else { ?>
			<?=CHtml::activeLabelEx($bank_account, 'swift', array('class' => 'control-label lefter'));?>
			<?=CHtml::activeTextField($bank_account, 'swift' , array('id' => 'CBankAccount_swift_field', 'class' => 'input-xlarge margin'));?>
			<?=CHtml::activeLabelEx($bank_account, 'iban', array('class' => 'control-label lefter'));?>
			<?=CHtml::activeTextField($bank_account, 'iban' , array('id' => 'CBankAccount_iban_field', 'class' => 'input-xlarge margin'));?>
		<?php }?>
	</fieldset>
</div>