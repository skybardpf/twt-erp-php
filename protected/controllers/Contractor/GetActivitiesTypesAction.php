<?php
/**
 * Возращает вид деятельности по переданой строке поиска.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class GetActivitiesTypesAction extends CAction
{
    /**
     * Только Ajax.
     * Возращает вид деятельности по переданой строке поиска.
     * @throws CHttpException
     */
    public function run()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $values = array();

            if (isset($_GET['id']) && $_GET['id']) {
                /**
                 * @var $model ContractorTypesActivities
                 */
                $arr = ContractorTypesActivities::getValues();
                if (isset($arr[$_GET['id']])){
                    $values = array(
                        'id' => $_GET['id'],
                        'text' => mb_substr($_GET['id'].' - '.$arr[$_GET['id']], 0, 50)
                    );
                }

                // Автодополнение селекта
            } elseif ($_GET['q'] && mb_strlen($_GET['q']) >= 4) {
                $q = $_GET['q'];
                $arr = ContractorTypesActivities::getValues();
                $q = mb_convert_case($q, MB_CASE_LOWER, "UTF-8");
                array_walk($arr, function($val, $key) use ($q, &$values) {
                    if (mb_strpos(mb_convert_case($val, MB_CASE_LOWER, "UTF-8"), $q) !== false || mb_stripos($key, $q) !== false) {
                        $values[] = array(
                            'id' => $key,
                            'text' => $key.' - '.$val
                        );
                    }
                });
            }

            echo CJSON::encode(array('values' => $values));
            Yii::app()->end();
        }
    }
}