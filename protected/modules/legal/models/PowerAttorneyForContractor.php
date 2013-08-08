<?php
/**
 * Модель: Доверенность для контрагента.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class PowerAttorneyForContractor extends PowerAttorneyAbstract
{
    /**
     * @return string
     */
    public function getTypeOrganization(){
        return MTypeOrganization::CONTRACTOR;
    }

	/**
	 * @static
	 * @param string $className
	 * @return PowerAttorneyForContractor
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}
}
