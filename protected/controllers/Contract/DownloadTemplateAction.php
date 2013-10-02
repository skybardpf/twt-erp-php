<?php
/**
 * Only Ajax. Отдает на скачивание шаблон контракта.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class DownloadTemplateAction extends CAction
{
    /**
     * Only Ajax. Отдает на скачивание шаблон контракта.
     * @param string $id
     * @param string $tid
     */
    public function run($id, $tid)
    {
        try {
            $template = ContractTemplate::model()->findByPk($id, $tid, true);
            echo CJSON::encode(array(
                    'success' => true,
                    'path' => TemplateLibraryGroup::decodePath($template->path),
                    'dpath' => $template->path,
                )
            );
        } catch(CException $e){
            echo CJSON::encode(array(
                    'success' => false,
                    'message' => $e->getMessage(),
                )
            );
        }
    }
}