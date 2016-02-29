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

use Hochzwei\H2dmailsub\Domain\Model\Address;
use Hochzwei\H2dmailsub\Utility\MessageType;
use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * NotificationService
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
     * The emailService
     *
     * @var \Hochzwei\H2dmailsub\Service\EmailService
     * @inject
     */
    protected $emailService;

    /**
     * @param array $settings
     * @param Address $address
     * @param int $messageType
     */
    public function sendNotification($address, $messageType, $settings)
    {
        $sender = $settings['notification']['senderEmail'];
        $senderName = $settings['notification']['senderName'];

        switch ($messageType) {
            case MessageType::SUBSCRIPTION_CONFIRM:
                $subject = $settings['notification']['subject']['confirm'];
                $template = 'Notification/User/SubscriptionConfirm';
                break;
            case MessageType::SUBSCRIPTION_CONFIRMED:
                $subject = $settings['notification']['subject']['confirmed'];
                $template = 'Notification/User/SubscriptionConfirmed';
                break;
            case MessageType::SUBSCRIPTION_UNSUBSCRIBE:
                $subject = $settings['notification']['subject']['unsubscribe'];
                $template = 'Notification/User/RequestUnsubscribe';
                break;
            default:
                $subject = '';
                $template = '';
        }

        // Send e-mail to recipient
        $body = $this->getNotificationContent($address, $template);
        $this->emailService->sendEmailMessage($sender, $address->getEmail(), $subject, $body, $senderName);
    }
    
    /**
     * Returns the rendered HTML for the given template
     *
     * @param Address $address
     * @param string $template
     * @return string
     */
    public function getNotificationContent($address, $template)
    {
        $standaloneView = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
        $standaloneView->setFormat('html');
        $standaloneView->setLayoutRootPaths(
            [GeneralUtility::getFileAbsFileName('EXT:h2dmailsub/Resources/Private/Layouts')]
        );
        $standaloneView->setPartialRootPaths(
            [GeneralUtility::getFileAbsFileName('EXT:h2dmailsub/Resources/Private/Partials')]
        );
        $standaloneView->setTemplateRootPaths(
            [GeneralUtility::getFileAbsFileName('EXT:h2dmailsub/Resources/Private/Templates')]
        );
        $standaloneView->setTemplate($template);
        $standaloneView->assign('address', $address);
        $emailBody = $standaloneView->render();
        return $emailBody;

        //@todo: variable RootPaths
        //@todo: variable Template Files
    }
}