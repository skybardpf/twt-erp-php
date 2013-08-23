<?php
/**
 * Список доступных документов для юр. лица.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var DocumentsController                $this                           Контролер
 * @var FoundingDocument[]                 $founding_docs                  Учредительные документы
 * @var PowerAttorneyForOrganization[]     $power_attorneys_docs           Доверенности
 * @var FreeDocument[]                     $free_docs                      Свободные документы
 * @var Organization                       $organization
 */

/* Учредительные документы */
$this->renderPartial('/founding_document/list', array(
    'docs' => $founding_docs,
    'organization' => $organization
));
/* Доверенности */
$this->renderPartial('/power_attorney_organization/list', array(
    'data' => $power_attorneys_docs,
    'organization' => $organization
));
/* Свободные документы */
$this->renderPartial('/free_document/list', array(
    'docs' => $free_docs,
    'organization' => $organization
));