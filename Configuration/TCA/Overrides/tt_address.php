<?php

if (!isset($GLOBALS['TCA']['tt_address']['ctrl']['type'])) {
	if (file_exists($GLOBALS['TCA']['tt_address']['ctrl']['dynamicConfigFile'])) {
		require_once($GLOBALS['TCA']['tt_address']['ctrl']['dynamicConfigFile']);
	}
	// no type field defined, so we define it here. This will only happen the first time the extension is installed!!
	$GLOBALS['TCA']['tt_address']['ctrl']['type'] = 'tx_extbase_type';
	$tempColumnstx_h2dmailsub_tt_address = array();
	$tempColumnstx_h2dmailsub_tt_address[$GLOBALS['TCA']['tt_address']['ctrl']['type']] = array(
		'exclude' => 1,
		'label'   => 'LLL:EXT:h2dmailsub/Resources/Private/Language/locallang_db.xlf:tx_h2dmailsub.tx_extbase_type',
		'config' => array(
			'type' => 'select',
			'renderType' => 'selectSingle',
			'items' => array(
				array('Address','Tx_H2dmailsub_Address')
			),
			'default' => 'Tx_H2dmailsub_Address',
			'size' => 1,
			'maxitems' => 1,
		)
	);
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_address', $tempColumnstx_h2dmailsub_tt_address, 1);
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
	'tt_address',
	$GLOBALS['TCA']['tt_address']['ctrl']['type'],
	'',
	'after:' . $GLOBALS['TCA']['tt_address']['ctrl']['label']
);

$tmp_h2dmailsub_columns = array(

	'localgender' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:h2dmailsub/Resources/Private/Language/locallang_db.xlf:tx_h2dmailsub_domain_model_address.localgender',
		'config' => array(
			'type' => 'input',
			'size' => 30,
			'eval' => 'trim'
		),
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_address',$tmp_h2dmailsub_columns);

/* inherit and extend the show items from the parent class */

if(isset($GLOBALS['TCA']['tt_address']['types']['1']['showitem'])) {
	$GLOBALS['TCA']['tt_address']['types']['Tx_H2dmailsub_Address']['showitem'] = $GLOBALS['TCA']['tt_address']['types']['1']['showitem'];
} elseif(is_array($GLOBALS['TCA']['tt_address']['types'])) {
	// use first entry in types array
	$tt_address_type_definition = reset($GLOBALS['TCA']['tt_address']['types']);
	$GLOBALS['TCA']['tt_address']['types']['Tx_H2dmailsub_Address']['showitem'] = $tt_address_type_definition['showitem'];
} else {
	$GLOBALS['TCA']['tt_address']['types']['Tx_H2dmailsub_Address']['showitem'] = '';
}
$GLOBALS['TCA']['tt_address']['types']['Tx_H2dmailsub_Address']['showitem'] .= ',--div--;LLL:EXT:h2dmailsub/Resources/Private/Language/locallang_db.xlf:tx_h2dmailsub_domain_model_address,';
$GLOBALS['TCA']['tt_address']['types']['Tx_H2dmailsub_Address']['showitem'] .= 'localgender';

$GLOBALS['TCA']['tt_address']['columns'][$GLOBALS['TCA']['tt_address']['ctrl']['type']]['config']['items'][] = array('LLL:EXT:h2dmailsub/Resources/Private/Language/locallang_db.xlf:tt_address.tx_extbase_type.Tx_H2dmailsub_Address','Tx_H2dmailsub_Address');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
	'',
	'EXT:/Resources/Private/Language/locallang_csh_.xlf'
);