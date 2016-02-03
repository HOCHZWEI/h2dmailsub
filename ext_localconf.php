<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Hochzwei.' . $_EXTKEY,
	'Pidmailsubscribe',
	array(
		'Address' => 'subscribe, saveSubscription, confirmSubscription, unsubscribe',
	),
	// non-cacheable actions
	array(
		'Address' => 'subscribe, saveSubscription, confirmSubscription, unsubscribe',
	)
);
