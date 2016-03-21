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
use TYPO3\CMS\Core\Mail\MailMessage;

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
     * The configurationManager
     *
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager
     * @inject
     */
    protected $configurationManager;


    /**
     * @param array $settings
     * @param Address $address
     * @param int $messageType
     * @param string $confirmationCode
     */
    public function sendNotification($address, $messageType, $settings, $confirmationCode = null)
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
        $htmlBody = $this->getNotificationContent($address, $template, $settings, $confirmationCode, 'html');
        $plainBody = $this->getNotificationContent($address, $template, $settings, $confirmationCode, 'txt');
        /** @var MailMessage $message */
        $message = GeneralUtility::makeInstance(MailMessage::class);
        $message->setTo($address->getEmail())
            ->setFrom([$sender => $senderName])
            ->setSubject($subject)
            ->setCharset('utf-8');
        if ($address->getReceiveHtml()) {
            $message->setBody($htmlBody, 'text/html');
            $message->addPart($plainBody, 'text/plain');
        } else {
            $message->setBody($plainBody, 'text/plain');
        }
        $message->send();
    }

    /**
     * @param array $settings
     * @param Address $address
     * @param int $messageType
     */
    public function sendAdminNotification($address, $messageType, $settings)
    {
        $sender = $settings['notification']['senderEmail'];
        $senderName = $settings['notification']['senderName'];
        $adminemail = $settings['notification']['adminEmail'];

        switch ($messageType) {
            case MessageType::SUBSCRIPTION_CONFIRMED:
                $subject = $settings['notification']['adminSubject']['confirmed'];
                $template = 'Notification/Admin/NewSubscription';
                break;
            case MessageType::SUBSCRIPTION_UNSUBSCRIBE:
                $subject = $settings['notification']['adminSubject']['unsubscribe'];
                $template = 'Notification/Admin/DeletedSubscription';
                break;
            default:
                $subject = '';
                $template = '';
        }

        // Send e-mail to admin
        $htmlBody = $this->getNotificationContent($address, $template, $settings, null, 'html');
        $plainBody = $this->getNotificationContent($address, $template, $settings, null, 'txt');
        /** @var MailMessage $message */
        $message = GeneralUtility::makeInstance(MailMessage::class);
        $message->setTo($adminemail)
            ->setFrom([$sender => $senderName])
            ->setSubject($subject)
            ->setCharset('utf-8');

        $message->setBody($htmlBody, 'text/html');
        $message->addPart($plainBody, 'text/plain');

        $message->send();
    }

    /**
     * Returns the rendered HTML for the given template
     *
     * @param Address $address
     * @param string $template
     * @param array $settings
     * @param string $confirmationCode
     * @param string $format
     * @return string
     */
    public function getNotificationContent($address, $template, $settings, $confirmationCode = null, $format)
    {
        $standaloneView = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
        $standaloneView->setFormat($format);

        $frameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);

        $standaloneView->setLayoutRootPaths(
            $frameworkConfiguration['view']['layoutRootPaths']
        );
        $standaloneView->setPartialRootPaths(
            $frameworkConfiguration['view']['partialRootPaths']
        );
        $standaloneView->setTemplateRootPaths(
            $frameworkConfiguration['view']['templateRootPaths']
        );
        $standaloneView->setTemplate($template);
        $standaloneView->assign('address', $address);
        $standaloneView->assign('senderSignature', $settings['notification']['senderSignature']);
        $standaloneView->assign('confirmationCode', $confirmationCode);
        $emailBody = $standaloneView->render();
        return $emailBody;
    }
}