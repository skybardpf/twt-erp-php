<?php
/**
 * Список шаблонов файлов в библиотеке шаблонов. Сгруппированны по группам.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class IndexAction extends CAction
{
    /**
     * Список шаблонов файлов в библиотеке шаблонов.
     */
    public function run()
    {
        /**
         * @var Template_libraryController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список шаблонов';

        $forceCached = (Yii::app()->request->getQuery('force_cache') == 1);
        $templates = TemplateLibrary::model()->getDataGroupBy($forceCached);
        $groups = TemplateLibraryGroup::model()->getTreeTemplates($templates, $forceCached);

        $controller->render(
            'index',
            array(
                'groups' => $groups,
                'forceCached' => $forceCached
            )
        );
    }
}