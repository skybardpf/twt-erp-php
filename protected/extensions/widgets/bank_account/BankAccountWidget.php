<?php
/**
 * Форма создания / редактирования банковского счета
 */
class BankAccountWidget extends CWidget
{
	/**
	 * @var CBankAccount Аккаунты компании
	 */
	public $bank_account = null;
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
		return $this->render('bank_account', array(
			'bank_account'     => $this->bank_account,
			'company_id'        => $this->company_id,
			'company_resident'  => $this->company_resident,
			'class'             => $this->class,
		));
	}
}

