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
 * EmailService
 */
class EmailService
{
    /**
     * Mailmessage
     *
     * @var \TYPO3\CMS\Core\Mail\MailMessage
     */
    protected $mail = null;

    /**
     * Constructor - creates new instance of mailer
     */
    public function __construct()
    {
        $this->mail = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Mail\\MailMessage');
    }

    /**
     * Sends an e-mail, if sender and recipient is an valid e-mail address
     *
     * @param string $sender The sender
     * @param string $recipient The recipient
     * @param string $subject The subject
     * @param string $body E-Mail body
     * @param string $name Optional sendername
     *
     * @return bool true/false if message is sent
     */
    public function sendEmailMessage($sender, $recipient, $subject, $body, $name = null)
    {
        if (GeneralUtility::validEmail($sender) && GeneralUtility::validEmail($recipient)) {
            $this->mail->setFrom($sender, $name);
            $this->mail->setSubject($subject);
            $this->mail->setBody($body, 'text/html');
            $this->mail->setTo($recipient);
            $this->mail->send();
            return $this->mail->isSent();
        } else {
            return false;
        }
    }
}