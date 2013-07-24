<?php
/**
 * Only Ajax. Возращает HTML форму с со списком стран.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class GetCountriesAction extends CAction
{
    /**
     * Только Ajax. Рендерим форму со списком стран. Показываются только страны,
     * которые еще не привязанны к данному событию.
     */
    public function run()
    {
        try {
            $countries = Countries::getValues();
            if (isset($_POST['selected_ids']) && !empty($_POST['selected_ids'])){
                $sel = CJSON::decode($_POST['selected_ids']);
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