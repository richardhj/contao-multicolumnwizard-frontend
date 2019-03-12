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

use Contao\Controller;
use Contao\Environment;
use Contao\Widget;


/**
 * Class FormMultiColumnWizard
 *
 * @package Richardhj\Contao
 */
class FormMultiColumnWizard extends \MenAtWork\MultiColumnWizardBundle\Contao\Widgets\MultiColumnWizard
{

    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'form_mcw';


    /**
     * The CSS class prefix
     *
     * @var string
     */
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
    }

    public function generate($overwriteRowCurrentRow = null, $onlyRows = false): string
    {
        $return = parent::generate($overwriteRowCurrentRow, $onlyRows);

        unset($GLOBALS['TL_JAVASCRIPT']['mcw'], $GLOBALS['TL_CSS']['mcw']);

        $GLOBALS['TL_JQUERY']['mcw_fe'] = 'bundles/richardhjcontaomulticolumnwizardfrontend/jquery.multicolumnwizard_fe.js';

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
    )
    {
        $return = parent::generateTable($arrUnique, $arrDatepicker, $arrColorpicker, $strHidden, $arrItems, $arrHiddenHeader, $onlyRows);

        // fixme does not work
        $return = preg_replace('/<script(.*?)>(.*?)<\/script>/im', '', $return);

        return $return;
    }

    /**
     * Generate button string
     *
     * @param int $level
     *
     * @return string
     */
    protected function generateButtonString($level = 0)
    {
        $return = '';

        // Add buttons
        foreach ($this->arrButtons as $button => $image) {
            if (false === $image) {
                continue;
            }

            $return .= sprintf(
                '<a data-operations="%s" href="%s" class="widgetImage" title="%s">%s</a> ',
                $button,
                str_replace(
                    'index.php',
                    strtok(Environment::get('requestUri'), '?'),
                    Controller::addToUrl(
                        http_build_query(
                            [
                                $this->strCommand => $button,
                                'cid' => $level,
                                'id' => $this->currentRecord,
                            ]
                        ),
                        false
                    )
                ),
                $GLOBALS['TL_LANG']['MSC']['tw_r' . specialchars($button)],
                $this->getButtonContent($button) # We don't want to output an image and don't provide $image
            );
        }

        return $return;
    }


    /**
     * Get the content of the button, either text or image
     *
     * @param string $button The button name
     *
     * @return string
     */
    protected function getButtonContent($button)
    {
        return '<span class="button ' . $button . '"></span>';
    }

}
