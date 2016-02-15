<?php
namespace Hochzwei\H2dmailsub\Service;
/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use \TYPO3\CMS\Core\Utility\GeneralUtility;
/**
 * NotificationService
 *
 */
class NotificationService
{
    /**
     * The object manager
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager;

    /**
     * Returns the rendered HTML for the given template
     *
     * @param int $uid
     * @return string
     */
    public function getNotificationContent($uid)
    {
        $standaloneView = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
        $standaloneView->setFormat('html');
        $standaloneView->setLayoutRootPaths(
            array(GeneralUtility::getFileAbsFileName('EXT:h2dmailsub/Resources/Private/Layouts'))
        );
        $standaloneView->setPartialRootPaths(
            array(GeneralUtility::getFileAbsFileName('EXT:h2dmailsub/Resources/Private/Partials'))
        );
        $standaloneView->setTemplateRootPaths(
            array(GeneralUtility::getFileAbsFileName('EXT:h2dmailsub/Resources/Private/Templates'))
        );
        $standaloneView->setTemplate('Notification/User/SubscribeLink');
        $standaloneView->assign('uid',$uid);
        $emailBody = $standaloneView->render();
        return $emailBody;

        //@todo: variable RootPaths
        //@todo: variable Template Files
    }
}