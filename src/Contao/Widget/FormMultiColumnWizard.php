<?php

/**
 * This file is part of richardhj/contao-multicolumnwizard-frontend.
 *
 * Copyright (c) 2016-2017 Richard Henkenjohann
 *
 * @package   richardhj/contao-multicolumnwizard-frontend
 * @author    Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 * @copyright 2016-2017 Richard Henkenjohann
 * @license   https://github.com/richardhj/contao-multicolumnwizard-frontend/blob/master/LICENSE LGPL-3.0
 */

namespace Richardhj\ContaoMultiColumnWizardFrontendBundle\Contao\Widget;

use Contao\{StringUtil, System, Widget};


/**
 * Class FormMultiColumnWizard
 *
 * @package Richardhj\Contao
 */
class FormMultiColumnWizard extends \MenAtWork\MultiColumnWizardBundle\Contao\Widgets\MultiColumnWizard
{

    protected $strTemplate = 'form_mcw';

    protected $strPrefix = 'widget widget-mcw';

    /** @noinspection PhpMissingParentConstructorInspection
     * Don't use parent's but parent parent's __construct
     *
     * @param array|null $arrAttributes
     */
    public function __construct($arrAttributes = null)
    {
        /** @noinspection PhpDynamicAsStaticMethodCallInspection */
        Widget::__construct($arrAttributes);

        $this->eventDispatcher = System::getContainer()->get('event_dispatcher');
    }

    public function generate($overwriteRowCurrentRow = null, $onlyRows = false): string
    {
        $return = parent::generate($overwriteRowCurrentRow, $onlyRows);

        unset($GLOBALS['TL_JAVASCRIPT']['mcw'], $GLOBALS['TL_CSS']['mcw']);

        $GLOBALS['TL_JQUERY']['mcw_fe'] =
            'bundles/richardhjcontaomulticolumnwizardfrontend/jquery.multicolumnwizard_fe.js';

        return $return;
    }

    protected function generateTable(
        $arrUnique,
        $arrDatepicker,
        $arrColorpicker,
        $strHidden,
        $arrItems,
        $arrHiddenHeader = array(),
        $onlyRows = false
    ): string {
        $return = parent::generateTable(
            $arrUnique,
            $arrDatepicker,
            $arrColorpicker,
            $strHidden,
            $arrItems,
            $arrHiddenHeader,
            $onlyRows
        );

        $return = preg_replace('/<script(.*?)>([\s\S]*?)<\/script>/im', '', $return);

        return $return;
    }

    protected function generateButtonString($level = 0): string
    {
        $return = '';

        // Add buttons
        foreach ($this->arrButtons as $button => $image) {
            if (false === $image) {
                continue;
            }

            $return .= sprintf(
                '<a data-operations="%s" href="#" class="widgetImage" title="%s">%s</a> ',
                $button,
                $GLOBALS['TL_LANG']['MSC']['tw_r' . StringUtil::specialchars($button)],
                $this->getButtonContent($button) # We don't want to output an image and don't provide $image
            );
        }

        return $return;
    }

    protected function getButtonContent($button): string
    {
        return '<span class="button ' . $button . '"></span>';
    }

    protected function getMcWDatePickerString(
        $fieldId,
        $fieldName,
        $rgxp = null,
        $fieldConfiguration = null,
        $tableName = null
    ): string {
        return '';
    }
}
