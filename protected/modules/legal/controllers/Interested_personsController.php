<?php
/**
 * Управление моделью: "Заинтересованные персоны".
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 */
class Interested_personsController extends Controller {
    public $layout = 'inner';
    public $menu_current = 'legal';
    public $current_tab = 'interested_persons';
	public $pageTitle = 'TWT Consult - Мои организации';

    public function actions()
    {
        return array(
            'index' => 'application.modules.legal.controllers.InterestedPerson.IndexAction',
            'view' => 'application.modules.legal.controllers.InterestedPerson.ViewAction',
            'add' => 'application.modules.legal.controllers.InterestedPerson.CreateAction',
            'edit' => 'application.modules.legal.controllers.InterestedPerson.UpdateAction',
            'delete' => 'application.modules.legal.controllers.InterestedPerson.DeleteAction',
        );
    }

    /**
     * Список заинтересованных лиц, по ролям. [Роль => Персона]
     *
     * @param string $org_id
     * @return InterestedPerson[]
     */
    public function getIndexProviderModel($org_id)
    {
        $data = InterestedPerson::model()
//            ->where('deleted', false)
//            ->where('id_yur', $org_id)
            ->findAll();

        $roles = array();
        foreach($data as $v){
            $roles[$v->role][] = $v;
        }

        $roles[InterestedPerson::ROLE_BENEFICIARY] = Beneficiary::model()
//            ->where('deleted', false)
//            ->where('id_yur', $org_id)
            ->findAll();

        return $roles;
    }

    /**
     * @param string $id_yur
     * @param string $role
     * @return InterestedPerson
     */
    public function createModel($id_yur, $role)
    {
        $model = new InterestedPerson();
        $model->id_yur = $id_yur;
        $model->role = $role;
        $model->type_yur = 'Организации'; // default

        return $model;
    }

    /**
     * @param string $id
     * @param string $id_yur
     * @param string $role
     * @return InterestedPerson
     * @throws CHttpException
     */
    public function loadModel($id, $id_yur, $role)
    {
        $model = InterestedPerson::model()->findByPk($id, $id_yur, $role);
        if ($model === null) {
            throw new CHttpException(404, 'Не найдено заинтересованное лицо.');
        }
        return $model;
    }

    /**
     * @param string $id
     * @param string $id_yur
     * @param string $numPack
     * @return InterestedPerson
     * @throws CHttpException
     */
    public function loadModelBeneficiary($id, $id_yur, $numPack)
    {
        $model = Beneficiary::model()->findByPk($id, $id_yur, $numPack);
        if ($model === null) {
            throw new CHttpException(404, 'Не найдено заинтересованное лицо.');
        }
        $model->role = InterestedPerson::ROLE_BENEFICIARY;
        $model->type_yur = "Организации";
        return $model;
    }

    /**
     * @param InterestedPerson $model
     */
    public function performAjaxValidation(InterestedPerson $model){
        if(isset($_POST['ajax']) && $_POST['ajax']==='interested-person-form'){
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
