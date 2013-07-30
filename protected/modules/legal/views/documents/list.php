<?php
/**
 *  Список доступных документов для юр. лица.
 *
 *  @var $this                  DocumentsController         Контролер
 *  @var $founding_docs         FoundingDocument[]          Учредительные документы
 *  @var $power_attorneys_docs  PowerAttorneysLE[]          Доверенности
 *  @var $free_docs             FreeDocument[]              Свободные документы
 *  @var $organization          Organization
 */

/* Учредительные документы */
$this->renderPartial('/founding_documents/list', array(
    'docs' => $founding_docs,
    'organization' => $organization
));
/* Доверенности */
$this->renderPartial('/power_attorney_le/list', array(
    'docs' => $power_attorneys_docs,
    'organization' => $organization
));
/* Свободные документы */
$this->renderPartial('/free_documents/list', array(
    'docs' => $free_docs,
    'organization' => $organization
));