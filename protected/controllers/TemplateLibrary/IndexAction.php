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
        $templates = TemplateLibrary::model()->listModels($forceCached);
        $data = TemplateLibraryGroup::model()->getTreeTemplates($templates, $forceCached);
        var_dump($data);die;

        $controller->render(
            'index',
            array(
                'data' => $data,
                'forceCached' => $forceCached
            )
        );
    }
}