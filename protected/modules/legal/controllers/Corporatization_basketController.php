<?php
/**
 * Корзина акционирования
 *
 * @author Burtsev R.V. <roman@artektiv.ru>
 */
define('TYPE_ORGANIZATIONS', 'Контрагент');
define('TYPE_INDIVIDUALS', 'ФизЛицо');

class Corporatization_basketController extends Controller
{
    public $layout = 'inner';
    public $menu_current = 'corporatization_basket';
    public $pageTitle = 'TWT Consult | Корзина акционирования';

    private $_titles = array(
            'organizations' => array(),
            'individuals' => array(),
        );

    public function actionIndex()
    {
        $this->_titles['organizations'] = Organization::getValues();
        $this->_titles['individuals'] = Individuals::getValues();

        /* --------- ДАННЫЕ*/
        $raw_data = '[
            {id1: "000000004", type1: "Контрагент", id2: "0000000002", type2: "ФизЛицо", percent: "50"},
            {id1: "000000008", type1: "Контрагент", id2: "0000000002", type2: "ФизЛицо", percent: "60"},
            {id1: "000000009", type1: "Контрагент", id2: "0000000002", type2: "ФизЛицо", percent: "60"},
            {id1: "000000004", type1: "Контрагент", id2: "000000008", type2: "Контрагент", percent: "60"},
        ]';
        /*$raw_data = '[
            {id1: "000000004", type1: "Контрагент", id2: "0000000002", type2: "ФизЛицо", percent: "50"}
        ]';*/
        /* --------- ДАННЫЕ*/

        preg_match_all('/\{id1: "(.*)", type1: "(.*)", id2: "(.*)", type2: "(.*)", percent: "(.*)"\}+/', $raw_data, $matches);

        $basketObject = '[';
        $count = count($matches[0]);

        for ($i=0; $i<$count; $i++)
        {
            $title1 = $matches[1][$i];
            $title2 = $matches[3][$i];
            $type1 = '';
            $type2 = '';

            if (TYPE_ORGANIZATIONS == $matches[2][$i]) {
                if (!empty($this->_titles['organizations'][$matches[1][$i]])) {
                    $title1 = $this->_titles['organizations'][$matches[1][$i]];
                    $type1 = 'rectangle';
                }
            }
            else if (TYPE_INDIVIDUALS == $matches[2][$i]) {
                if (!empty($this->_titles['individuals'][$matches[1][$i]])) {
                    $title1 = $this->_titles['individuals'][$matches[1][$i]];
                    $type1 = 'circle';
                }
            }
            if (TYPE_ORGANIZATIONS == $matches[4][$i]) {
                if (!empty($this->_titles['organizations'][$matches[3][$i]])) {
                    $title2 = $this->_titles['organizations'][$matches[3][$i]];
                    $type2 = 'rectangle';
                }
            }
            else  if (TYPE_INDIVIDUALS == $matches[4][$i]) {
                if (!empty($this->_titles['individuals'][$matches[3][$i]])) {
                    $title2 = $this->_titles['individuals'][$matches[3][$i]];
                    $type2 = 'circle';
                }
            }

            $basketObject.='{ id1: "'.$matches[1][$i].'", type1: "'.$type1.'", title1: "'.$title1.'", id2: "'.$matches[3][$i].'", type2: "'.$type2.'", title2: "'.$title2.'", percent: "'.$matches[5][$i].'"}';
            if ($i != $count-1)
                $basketObject .= ',';
        }
        $basketObject .= ']';
        $this->render('/corporatization_basket/index', array('basketObject' => $basketObject));
    }
}