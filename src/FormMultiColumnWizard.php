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

namespace Richardhj\Contao;

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

        $this->eventDispatcher = \System::getContainer()->get('event_dispatcher');
    }

    public function generate($overwriteRowCurrentRow = null, $onlyRows = false)
    {
        $return = parent::generate($overwriteRowCurrentRow, $onlyRows);

        unset($GLOBALS['TL_JAVASCRIPT']['mcw'], $GLOBALS['TL_CSS']['mcw']);

        $GLOBALS['TL_JQUERY'][] = <<<'HTML'
<script>
    $(function () {
        $(document).on('click', 'table.multicolumnwizard a[data-operations="new"]', function (event) {
            event.preventDefault();

            var clonedRow = $(this).closest('tr').clone();
            clonedRow.find('input').val('');
            $(this).closest('tr').after(clonedRow);

            updateMcwInputs($(this).closest('table'));
        });

        $(document).on('click', 'table.multicolumnwizard a[data-operations="up"]', function (event) {
            event.preventDefault();
            var row = $(this).parents('tr:first');
            row.insertBefore(row.prev());

            updateMcwInputs($(this).closest('table'));
        });

        $(document).on('click', 'table.multicolumnwizard a[data-operations="down"]', function (event) {
            event.preventDefault();
            var row = $(this).parents('tr:first');
            row.insertAfter(row.prev());

            updateMcwInputs($(this).closest('table'));
        });

        $(document).on('click', 'table.multicolumnwizard a[data-operations="delete"]', function (event) {
            event.preventDefault();
            $(this).closest('tr').remove();

            updateMcwInputs($(this).closest('table'));
        });

        function updateMcwInputs($table) {
            $table.find('tr[data-rowid]').each(function (index) {
                $(this).attr('data-rowid', index);

                $(this).find('label[for]').each(function () {
                    $(this).attr('for', $(this).attr('for').replace(/(.+?)(\[\d])(\[.+?])/, '$1[' + index + ']$3'))
                });

                $(this).find('*[name][id]').each(function () {
                    var name = $(this).attr('name').replace(/(.+?)(\[\d])(\[.+?])/, '$1[' + index + ']$3');
                    $(this).attr('name', name);
                    $(this).attr('id', 'ctrl_' + name);
                });
            });
        }

    }(jQuery));
</script>
HTML;

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
    ) {
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
     *
     * @return string
     */
    protected function getButtonContent($button)
    {
        return '<span class="button ' . $button . '"></span>';
    }


    /**
     * {@inheritdoc}
     */
    protected function getMcWDatePickerString(
        $fieldId,
        $fieldName,
        $rgxp = null,
        $fieldConfiguration = null,
        $tableName = null
    ) {
        return '';
    }

}
