<?php
/**
 * Class MViewInterestedPerson
 * Вид заинтересованного лица.
 */
class MViewInterestedPerson extends CEnumerable
{
    const SHAREHOLDER = 'НоминальныйАкционер';
    const LEADER = 'Директор';
    const SECRETARY = 'Секретарь';
    const MANAGER = 'Менеджер';
    const BENEFICIARY = 'Бенефициар';
}