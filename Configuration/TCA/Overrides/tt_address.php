<?php
defined('TYPO3_MODE') or die();

$fields = [
    'localgender' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:h2dmailsub/Resources/Private/Language/locallang_db.xlf:tx_h2dmailsub_domain_model_address.localgender',
        'config' => [
            'type' => 'radio',
            'items' => array(
                array('LLL:EXT:h2dmailsub/Resources/Private/Language/locallang_db.xlf:tx_h2dmailsub_domain_model_address.localgender.female', 'f'),
                array('LLL:EXT:h2dmailsub/Resources/Private/Language/locallang_db.xlf:tx_h2dmailsub_domain_model_address.localgender.male', 'm'),
                array('LLL:EXT:h2dmailsub/Resources/Private/Language/locallang_db.xlf:tx_h2dmailsub_domain_model_address.localgender.none', 'n')
            )
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_address', $fields);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_address', 'localgender', '', 'after:gender');
