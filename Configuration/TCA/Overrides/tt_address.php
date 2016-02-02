<?php
defined('TYPO3_MODE') or die();

$fields = array (
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

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_address', $fields);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_address', 'localgender', '', 'after:gender');
