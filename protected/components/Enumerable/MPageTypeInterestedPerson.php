<?php
/**
 * Class MPageTypeInterestedPerson
 * Тип заинтересованного лица для страниц.
 */
class MPageTypeInterestedPerson extends CEnumerable
{
    const SHAREHOLDER = 'shareholder';
    const LEADER = 'leader';
    const SECRETARY = 'secretary';
    const MANAGER = 'manager';
    const BENEFICIARY = 'beneficiary';
}