<?php
/**
 * Форма создания / редактирования банковского счета
 */
class BankAccountsWidget extends CWidget
{
	/**
	 * @var CBankAccount Аккаунты компании
	 */
	public $bank_accounts = null;
	/**
	 * @var null Идентифиатор компании
	 */
	public $company_id = null;
	/**
	 * @var null Является ли компания резидентом
	 */
	public $company_resident = null;
	/**
	 * @var string CSS класс или набор классов через пробел, которые можно дописать к блоку
	 */
	public $class   = '';

	public function run()
	{
		Yii::app()->clientScript->registerScriptFile(CHtml::asset(dirname(__FILE__).'/assets/bank_accounts.js'));
		return $this->render('bank_accounts', array(
			'bank_accounts'     => $this->bank_accounts,
			'company_id'        => $this->company_id,
			'company_resident'  => $this->company_resident,
			'class'             => $this->class,
		));
	}
}

