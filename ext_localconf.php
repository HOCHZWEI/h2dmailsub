<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Hochzwei.' . $_EXTKEY,
	'Pidmailsubscribe',
	[
		'Address' => 'subscribe, saveSubscription, confirmSubscription, unsubscribe, requestUnsubscribe, removeSubscription',
	],
	// non-cacheable actions
	[
		'Address' => 'subscribe, saveSubscription, confirmSubscription, unsubscribe, requestUnsubscribe, removeSubscription',
	]
);
