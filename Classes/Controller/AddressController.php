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
     * SubscribeValidator
     *
     * @var \Hochzwei\H2dmailsub\Validation\SubscribeValidator
     * @inject
     */
    protected $subscribeValidator;

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
     * @validate $address \Hochzwei\H2dmailsub\Validation\SubscribeValidator
     * @return void
     */
    public function saveSubscriptionAction(\Hochzwei\H2dmailsub\Domain\Model\Address $address)
    {
        $email = $this->addressRepository->findAddressByEmail($address->getEmail());
        if ($email) {
            $knownEmail = true;
        } else {
            $knownEmail = false;
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
        }
        $this->view->assign('knownEmail', $knownEmail);
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

            $this->notificationService->sendNotification($address, MessageType::SUBSCRIPTION_CONFIRMED, $this->settings);
            $this->notificationService->sendAdminNotification($address, MessageType::SUBSCRIPTION_CONFIRMED, $this->settings);

            $already_confirmed = false;
        } else {
            $titleKey = 'confirm.title.already_confirmed';
            $messageKey = 'confirm.message.already_confirmed';
            $already_confirmed = true;
        }

        $this->view->assignMultiple([
            'titleKey' => $titleKey,
            'messageKey' => $messageKey,
            'already_confirmed' => $already_confirmed
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
            $this->notificationService->sendNotification($address, MessageType::SUBSCRIPTION_UNSUBSCRIBE, $this->settings);
            $unknownAddress = false;
        } else {
            $unknownAddress = true;
        }
        $this->view->assign('unknownAddress', $unknownAddress);
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
        if ($address) {
            $this->addressRepository->remove($address);
            $this->notificationService->sendAdminNotification($address, MessageType::SUBSCRIPTION_UNSUBSCRIBE, $this->settings);
            $deletedAddress = false;
        } else {
            $deletedAddress = true;
        }
        $this->view->assign('deletedAddress', $deletedAddress);
    }

}