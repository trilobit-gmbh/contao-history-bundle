<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2014 Leo Feyer
 *
 * @package   trilobit
 * @author    trilobit GmbH <http://www.trilobit.de>
 * @license   LPGL
 * @copyright trilobit GmbH
 */

/**
 * Namespace
 */
namespace Trilobit\HistoryBundle;

use Contao\Environment;
use FrontendTemplate;
use Module;
use Patchwork\Utf8;
use StringUtil;

class ModuleHistory extends Module
{

    protected $strTemplate = 'mod_history';


    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['history'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        // SESSION
        $objSessionBag = \System::getContainer()->get('session')->getBag('contao_frontend');
        $arrSession = $objSessionBag->all();


        $strHref = Environment::get('request');


        $key = array_search($strHref, array_column($arrSession['history'][$this->keyOfHistoryItems], 'href'));

        if (   $this->recentHistoryItems == 1
            && $key !== null
            && $key !== false
        )
        {
            array_splice($arrSession['history'][$this->keyOfHistoryItems], $key, 1);
        }

        global $objPage;

        $arrSession['history'][$this->keyOfHistoryItems][] = array
        (
            'href'      => $strHref,
            'title'     => $objPage->title,
            'pageTitle' => $objPage->pageTitle,
        );

        //$arrSession = array();

        $objSessionBag->replace($arrSession);

        $strBuffer = parent::generate();

        return ($this->Template->items != '') ? $strBuffer : '';
    }


    protected function compile()
    {
        /** @var PageModel $objPage */
        global $objPage;

        $items = array();

        $objSessionBag = \System::getContainer()->get('session')->getBag('contao_frontend');
        $arrSession = $objSessionBag->all();


        // Set default template
        if ($this->navigationTpl == '')
        {
            $this->navigationTpl = 'nav_default_history';
        }

        /** @var FrontendTemplate|object $objTemplate */
        $objTemplate = new FrontendTemplate($this->navigationTpl);

        $objTemplate->type  = get_class($this);
        $objTemplate->cssID = $this->cssID; // see #4897 and 6129

        if (   is_array($arrSession['history'][$this->keyOfHistoryItems])
            && !empty($arrSession['history'][$this->keyOfHistoryItems])
        )
        {
            foreach (array_reverse($arrSession['history'][$this->keyOfHistoryItems]) as $key => $value)
            {
                $row['title']     = StringUtil::specialchars($value['title'], true);
                $row['pageTitle'] = StringUtil::specialchars($value['pageTitle'], true);
                $row['href']      = $value['href'];
                $row['link']      = $value['title'];

                $items[] = $row;

                if (   $this->numberOfHistoryItems != 0
                    && $this->numberOfHistoryItems + 1 <= $key
                )
                {
                    break;
                }
            }

            // Add classes first and last
            $items[0]['class'] = trim($items[0]['class'] . ' first');
            $last = count($items) - 1;
            $items[$last]['class'] = trim($items[$last]['class'] . ' last');
        }

        $objTemplate->items = $items;

        $this->Template->request        = Environment::get('indexFreeRequest');
        $this->Template->skipId         = 'skipNavigation' . $this->id;
        $this->Template->skipNavigation = StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['skipNavigation']);
        $this->Template->items          = !empty($items) ? $objTemplate->parse() : '';
        $this->Template->session        = $arrSession['history'][$this->keyOfHistoryItems];
    }
}
