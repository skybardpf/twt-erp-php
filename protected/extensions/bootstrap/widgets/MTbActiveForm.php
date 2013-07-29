<?php

Yii::import('bootstrap.widgets.TbActiveForm');

/**
 * Bootstrap active form widget.
 */
class MTbActiveForm extends TbActiveForm
{
    /**
     * Displays the first validation error for a model attribute.
     * @param   CModel $model the data model
     * @param   string $attribute the attribute name
     * @param   array $htmlOptions additional HTML attributes to be rendered in the container div tag.
     * @return  string the error display. Empty if no errors are found.
     * @see     CModel::getErrors
     * @see     errorMessageCss
     */
    protected static function renderError($model, $attribute, $htmlOptions = array())
    {
        return '';
    }
}