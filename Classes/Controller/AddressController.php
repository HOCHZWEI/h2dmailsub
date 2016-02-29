<?php
namespace Hochzwei\H2dmailsub\Controller;

use Hochzwei\H2dmailsub\Utility\MessageType;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * AddressController
 *
 */
class AddressController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * addressRepository
     *
     * @var \Hochzwei\H2dmailsub\Domain\Repository\AddressRepository
     * @inject
     */
    protected $addressRepository = null;

    /**
     * Notification Service
     *
     * @var \Hochzwei\H2dmailsub\Service\NotificationService
     * @inject
     */
    protected $notificationService;

    /**
     * MessageType
     *
     * @var \Hochzwei\H2dmailsub\Utility\MessageType
     * @inject
     */
    protected $MessageType;

    /**
     * subscribe
     *
     * @return void
     */
    public function subscribeAction()
    {

    }

    /**
     * Saves a new subscription
     *
     * @todo Implement validator
     *
     * @param \Hochzwei\H2dmailsub\Domain\Model\Address $address
     * @return void
     */
    public function saveSubscriptionAction(\Hochzwei\H2dmailsub\Domain\Model\Address $address)
    {
        // @todo Only add, if address is not already subscribed

        $address->setHidden(true);
        $this->addressRepository->add($address);
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
        $persistenceManager->persistAll();
        $uid = $address->getUid();

        // @todo Send E-Mail with confirmation link if configured (should also contain a configurable validity and the UID of record)
        if ($this->settings['doubleOptIn']) {
            $this->notificationService->sendNotification($address, MessageType::SUBSCRIPTION_CONFIRM, $this->settings);
        } else {
            $this->redirect('confirmSubscription', null, null, ['subscriptionUid' => $uid]);
        }

        // @todo Show message (Subscription Saved / Subscription saved but needs to be confirmed)
    }

    /**
     * confirm a subscription
     *
     * @param int $subscriptionUid
     * @return void
     */
    public function confirmSubscriptionAction($subscriptionUid)
    {
        $titleKey = 'confirm.title.confirmed';
        $messageKey = 'confirm.message.confirmed';

        // @todo THA: Security checks


        /* @var $address \Hochzwei\H2dmailsub\Domain\Model\Address */
        $address = $this->addressRepository->findAddressByUid($subscriptionUid);

        if ($address->getHidden()) {
            $address->setHidden(false);
            $this->addressRepository->update($address);

            // @todo send email to recipient that subscription is confirmed and active
            $this->notificationService->sendNotification($address, MessageType::SUBSCRIPTION_CONFIRMED);
        } else {
            $titleKey = 'confirm.title.already_confirmed';
            $messageKey = 'confirm.message.already_confirmed';
        }

        $this->view->assignMultiple([
            'titleKey' => $titleKey,
            'messageKey' => $messageKey
        ]);
    }

    /**
     * unsubscribe
     *
     * @return void
     */
    public function unsubscribeAction()
    {

    }

    /**
     * Sends the unsubscribe link for the given e-mail address
     *
     * @param string $email
     */
    public function requestUnsubscribeAction($email)
    {
        $address = $this->addressRepository->findAddressByEmail($email);
        if ($address) {
            $uid = $address->getUid();
            $body = $this->notificationService->getNotificationContent($uid, 'Notification/User/RequestUnsubscribe');
            $this->emailService->sendEmailMessage('asc@hoch2.de', 'asc@hoch2.de', 'Unsubscription', $body);
        }

        // @todo: show message
    }

    /**
     * remove Address
     *
     * @param int $subscriptionUid
     * @return void
     */
    public function removeSubscriptionAction($subscriptionUid)
    {
        // @todo THA: Security checks

        /* @var $address \Hochzwei\H2dmailsub\Domain\Model\Address */
        $address = $this->addressRepository->findAddressByUid($subscriptionUid);
        if($address){
            $this->addressRepository->remove($address);
        }
        else{
            //@todo: Error Messages
        }
    }

}