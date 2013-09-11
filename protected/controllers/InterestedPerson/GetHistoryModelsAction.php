<?php
/**
 * Получение списка заинтересованных лиц из истории изменений. Only Ajax.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class GetHistoryModelsAction extends CAction
{
    /**
     * Получение списка заинтересованных лиц из истории изменений
     * @param string $org_id
     * @param string $org_type
     * @param string $date
     * @param string $type_person
     * @throws CHttpException
     */
    public function run($org_id, $org_type, $date, $type_person)
    {
        if (Yii::app()->request->isAjaxRequest){
            try {
                if ($org_type === MTypeOrganization::ORGANIZATION)
                    $org = Organization::model()->findByPk($org_id);
                elseif ($org_type === MTypeOrganization::CONTRACTOR)
                    $org = Contractor::model()->findByPk($org_id);
                else
                    throw new CException('Указан неизвестный тип организации.');

                switch ($type_person){
                    case MPageTypeInterestedPerson::LEADER: {
                        $model = new InterestedPersonLeader();
                    } break;
                    case MPageTypeInterestedPerson::MANAGER: {
                        $model = new InterestedPersonManager();
                    } break;
                    case MPageTypeInterestedPerson::SECRETARY: {
                        $model = new InterestedPersonSecretary();
                    } break;
                    case MPageTypeInterestedPerson::SHAREHOLDER: {
                        $model = new InterestedPersonShareholder();
                    } break;
                    case MPageTypeInterestedPerson::BENEFICIARY: {
                        $model = new InterestedPersonBeneficiary();
                    } break;
                    default: {
                        throw new CHttpException(404, 'Неизвестный тип заинтересованного лица');
                    }
                }

                $data = $model->listModels($org->primaryKey, $org->type, $date, $this->controller->getForceCached());
                $html = $this->controller->renderPartial(
                    '/interested_person_'.$type_person.'/_list_grid_view',
                    array(
                        'data' => $data
                    ),
                    true
                );
                echo CJSON::encode(array(
                    'success' => true,
                    'html' => $html
                ));
                Yii::app()->end(200);
            } catch (CException $e) {
                echo CJSON::encode(array(
                    'success' => false,
                    'message' => $e->getMessage()
                ));
                Yii::app()->end(400);
            }
        }
    }
}