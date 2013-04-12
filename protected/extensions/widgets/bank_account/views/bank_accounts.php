<?php
	/**
	 * @var $this BankAccountsWidget
	 * @var $bank_accounts array() CBankAccount
	 */
Yii::app()->clientScript->registerCssFile(CHtml::asset(Yii::app()->basePath.'/../static/css/main.css'));
?>
<div class="bordered controls">
	<div data-bank_accounts="1" class="left">
		<legend>Банковские счета</legend>
		<?php if($bank_accounts) : foreach($bank_accounts as $account) :?>
			<?php $this->widget('ext.widgets.bank_account.BankAccountWidget', array(
				'bank_account'  => $account,
				'company_id' => $company_id,
				'company_resident' => $company_resident
			));?>
		<?php endforeach; endif;?>

	</div>

	<div>
		<a class="btn btn-primary" href="<?=$this->controller->createUrl('update_account', array('company_id' => $company_id, 'resident' => 1))?>"
		   data-account_resident="1"
		   data-bank_account_link=""
		   style="display:<?=$company_resident ? 'inline' :'none'?>;">
			Добавить
		</a>
		<a class="btn btn-primary" href="<?=$this->controller->createUrl('update_account', array('company_id' => $company_id, 'resident' => 0))?>"
		   data-account_resident="0"
		   data-bank_account_link=""
		   style="display:<?=$company_resident ? 'none': 'inline'?>;">
			Добавить
		</a>
	</div>
</div>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'ModalWindow')); ?>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4 id="modal-header"></h4>
    </div>
    <div class="modal-body" id="modal-body"></div>
    <div class="modal-footer" id="modal-footer"></div>
<?php $this->endWidget(); ?>
