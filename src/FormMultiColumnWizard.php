<?php

/**
 * This file is part of richardhj/contao-multicolumnwizard-frontend.
 *
 * Copyright (c) 2017 Richard Henkenjohann
 *
 * @package   FormMultiColumnWizard
 * @author    Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 * @copyright 2017 Richard Henkenjohann
 * @license   https://github.com/MetaModels/attribute_translatedtabletext/blob/master/LICENSE LGPL-3.0
 */

namespace Richardhj\Contao;

use Contao\Controller;
use Contao\Environment;
use Contao\Widget;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Image\GenerateHtmlEvent;
use MultiColumnWizard;
use Symfony\Component\EventDispatcher\EventDispatcher;


/**
 * Class FormMultiColumnWizard
 *
 * @package Richardhj\Contao
 */
class FormMultiColumnWizard extends MultiColumnWizard
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
                '<a rel="%s" href="%s" class="widgetImage" title="%s">%s</a> ',
                $button,
                str_replace(
                    'index.php',
                    strtok(Environment::get('requestUri'), '?'),
                    Controller::addToUrl(
                        http_build_query(
                            [
                                $this->strCommand => $button,
                                'cid'             => $level,
                                'id'              => $this->currentRecord,
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
     * @param string $image  Provide src path if you want to use image buttons
     *
     * @return string
     */
    protected function getButtonContent($button, $image = '')
    {
        if ('' === $image) {
            return '<span class="button ' . $button . '"></span>';
        }

        $event = new GenerateHtmlEvent(
            $image,
            $GLOBALS['TL_LANG']['MSC']['tw_r' . specialchars($button)],
            'class="tl_listwizard_img"'
        );

        $this->getEventDispatcher()->dispatch(ContaoEvents::IMAGE_GET_HTML, $event);

        return $event->getHtml();
    }


    /**
     * Disable the date picker because it is not designed for front end
     *
     * @param string $strId
     * @param string $strKey
     * @param string $rgxp
     *
     * @return string
     */
    protected function getMcWDatePickerString($strId, $strKey, $rgxp)
    {
        return '';
    }

    /**
     * Return the event dispatcher
     *
     * @return EventDispatcher
     */
    protected function getEventDispatcher()
    {
        return $GLOBALS['container']['event-dispatcher'];
    }
}
