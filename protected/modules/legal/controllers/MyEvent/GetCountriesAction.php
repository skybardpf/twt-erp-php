<?php
/**
 * Only Ajax. Возращает HTML форму с со списком стран.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class GetCountriesAction extends CAction
{
    /**
     * Только Ajax. Рендерим форму со списком стран. Показываются только страны,
     * которые еще не привязанны к данному событию.
     */
    public function run()
    {
        if (Yii::app()->request->isAjaxRequest) {
            try {
                $countries = Country::getValues();
                if (isset($_POST['ids']) && !empty($_POST['ids'])){
                    $sel = CJSON::decode($_POST['ids']);
                    if ($sel !== null){
                        foreach ($sel as $k){
                            if (isset($countries[$k])){
                                unset($countries[$k]);
                            }
                        }
                    }
                }
                $countries = array_merge(array('' => 'Выберите'), $countries);

                $this->controller->renderPartial(
                    '/my_events/_get_form_countries',
                    array(
                        'data' => $countries,
                    ),
                    false
                );

            } catch (CException $e){
                echo $e->getMessage();
            }
        }
    }
}