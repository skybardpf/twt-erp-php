<?php
/**
 * ARuEmailValidator validates that the attribute value is a valid email address.
 */
class ARuEmailValidator extends CEmailValidator
{
	/**
	 * @var string the regular expression used to validate the attribute value.
	 * @see http://www.regular-expressions.info/email.html
	 */
	public $pattern='/^[a-zA-Z0-9а-яА-ЯёЁ!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9а-яА-ЯёЁ!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9а-яА-ЯёЁ](?:[a-zA-Z0-9а-яА-ЯёЁ-]*[a-zA-Z0-9а-яА-ЯёЁ])?\.)+[a-zA-Z0-9а-яА-ЯёЁ](?:[a-zA-Z0-9а-яА-ЯёЁ-]*[a-zA-Z0-9а-яА-ЯёЁ])?$/u';
	/**
	 * @var string the regular expression used to validate email addresses with the name part.
	 * This property is used only when {@link allowName} is true.
	 * @since 1.0.5
	 * @see allowName
	 */
	public $fullPattern='/^[^@]*<[a-zA-Z0-9а-яА-ЯёЁ!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9а-яА-ЯёЁ!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9а-яА-ЯёЁ](?:[a-zA-Z0-9а-яА-ЯёЁ-]*[a-zA-Z0-9а-яА-ЯёЁ])?\.)+[a-zA-Z0-9а-яА-ЯёЁ](?:[a-zA-Z0-9а-яА-ЯёЁ-]*[a-zA-Z0-9а-яА-ЯёЁ])?>$/u';

}
