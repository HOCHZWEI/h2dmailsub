<?php
namespace Hochzwei\H2dmailsub\Controller;

use Hochzwei\H2dmailsub\Utility\MessageType;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
        $titleKey = 'saveSubscription.title';
        $messageKey = 'saveSubscription';

        if ($email) {
            $titleKey = 'saveSubscription.failed.title';
            $messageKey = 'saveSubscription.failed';
        } else {
            $address->setHidden(true);
            $this->addressRepository->add($address);
            $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
            $persistenceManager->persistAll();
            $uid = $address->getUid();

            $confirmationCode = $this->generateConfirmationCode($uid);
            if ($this->settings['doubleOptIn']) {
                $this->notificationService->sendNotification($address, MessageType::SUBSCRIPTION_CONFIRM, $this->settings, $confirmationCode);
            } else {
                $this->redirect('confirmSubscription', null, null, ['subscriptionUid' => $uid]);
            }
        }
        $this->view->assignMultiple([
            'titleKey' => $titleKey,
            'messageKey' => $messageKey,
        ]);
    }

    /**
     * confirm a subscription
     *
     * @param int $subscriptionUid
     * @param string $confirmationCode
     * @return void
     */
    public function confirmSubscriptionAction($subscriptionUid, $confirmationCode)
    {
        $titleKey = 'confirmSubscription.title';
        $messageKey = 'confirmSubscription';

        $confirmationCodeValid = $this->validateConfirmationCode($subscriptionUid, $confirmationCode);
        if ($confirmationCodeValid == false) {
            $titleKey = 'confirmSubscription.failed.auth.title';
            $messageKey = 'confirmSubscription.failed.auth';
        } else {
            /* @var $address \Hochzwei\H2dmailsub\Domain\Model\Address */
            $address = $this->addressRepository->findAddressByUid($subscriptionUid);

            if ($address->getHidden()) {
                $address->setHidden(false);
                $this->addressRepository->update($address);

                $this->notificationService->sendNotification($address, MessageType::SUBSCRIPTION_CONFIRMED, $this->settings);
                $this->notificationService->sendAdminNotification($address, MessageType::SUBSCRIPTION_CONFIRMED, $this->settings);
            } else {
                $titleKey = 'confirmSubscription.failed.title';
                $messageKey = 'confirmSubscription.failed';
            }
        }
        $this->view->assignMultiple([
            'titleKey' => $titleKey,
            'messageKey' => $messageKey,
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
        $titleKey = 'requestUnsubscribe.title';
        $messageKey = 'requestUnsubscribe';

        $address = $this->addressRepository->findAddressByEmail($email);
        if ($address) {
            $confirmationCode = $this->generateConfirmationCode($address->getUid());
            $this->notificationService->sendNotification($address, MessageType::SUBSCRIPTION_UNSUBSCRIBE, $this->settings, $confirmationCode);
        } else {
            $titleKey = 'requestUnsubscribe.failed.title';
            $messageKey = 'requestUnsubscribe.failed';
        }
        $this->view->assignMultiple([
            'titleKey' => $titleKey,
            'messageKey' => $messageKey,
        ]);
    }

    /**
     * remove Address
     *
     * @param int $subscriptionUid
     * @param string $confirmationCode
     * @return void
     */
    public function removeSubscriptionAction($subscriptionUid, $confirmationCode)
    {
        $titleKey = 'removeSubscription.title';
        $messageKey = 'removeSubscription';

        $confirmationCodeValid = $this->validateConfirmationCode($subscriptionUid, $confirmationCode);
        if ($confirmationCodeValid == false) {
            $titleKey = 'removeSubscription.failed.auth.title';
            $messageKey = 'removeSubscription.failed.auth';
        } else {
            /* @var $address \Hochzwei\H2dmailsub\Domain\Model\Address */
            $address = $this->addressRepository->findAddressByUid($subscriptionUid);
            if ($address) {
                $this->addressRepository->remove($address);
                $this->notificationService->sendAdminNotification($address, MessageType::SUBSCRIPTION_UNSUBSCRIBE, $this->settings);
            } else {
                $titleKey = 'removeSubscription.failed.title';
                $messageKey = 'removeSubscription.failed';
            }
        }
        $this->view->assignMultiple([
            'titleKey' => $titleKey,
            'messageKey' => $messageKey,
        ]);
    }


    /**
     * @param integer $uid
     * @return string
     */
    private function generateConfirmationCode($uid)
    {
        return GeneralUtility::stdAuthCode($uid);
    }

    /**
     * @param integer $uid
     * @param string $confirmationCode
     * @return boolean
     */
    private function validateConfirmationCode($uid, $confirmationCode)
    {
        $confirmationCodeForUid = $this->generateConfirmationCode($uid);
        return $confirmationCodeForUid === $confirmationCode;
    }
}