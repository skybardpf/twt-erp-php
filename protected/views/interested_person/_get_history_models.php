<?php
/**
 * Список заинтересованных лиц: Номинальные акционеры
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Interested_personController $this
 * @var InterestedPersonAbstract[] $data
 * @var Organization $organization
 * @var string $type_person
 */
echo $this->renderPartial('/interested_person_'.$type_person.'/_list_grid_view', array(
    'data' => $data
), true);