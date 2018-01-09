<?php

/**
 * Table tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['history'] = '{title_legend},name,headline,type;{nav_legend},numberOfHistoryItems,keyOfHistoryItems,recentHistoryItems;{template_legend:hide},navigationTpl,customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';

$GLOBALS['TL_DCA']['tl_module']['fields']['recentHistoryItems']            = $GLOBALS['TL_DCA']['tl_module']['fields']['hardLimit'];
$GLOBALS['TL_DCA']['tl_module']['fields']['keyOfHistoryItems']             = $GLOBALS['TL_DCA']['tl_module']['fields']['name'];
$GLOBALS['TL_DCA']['tl_module']['fields']['numberOfHistoryItems']          = $GLOBALS['TL_DCA']['tl_module']['fields']['numberOfItems'];
$GLOBALS['TL_DCA']['tl_module']['fields']['recentHistoryItems']['label']   = &$GLOBALS['TL_LANG']['tl_module']['recentHistoryItems'];
$GLOBALS['TL_DCA']['tl_module']['fields']['keyOfHistoryItems']['label']    = &$GLOBALS['TL_LANG']['tl_module']['keyOfHistoryItems'];
$GLOBALS['TL_DCA']['tl_module']['fields']['numberOfHistoryItems']['label'] = &$GLOBALS['TL_LANG']['tl_module']['numberOfHistoryItems'];