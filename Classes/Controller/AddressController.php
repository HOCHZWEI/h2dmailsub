<?php
namespace Hochzwei\H2dmailsub\Controller;

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
     * Email Service
     *
     * @var \Hochzwei\H2dmailsub\Service\EmailService
     * @inject
     */
    protected $emailService;

    /**
     * Notification Service
     *
     * @var \Hochzwei\H2dmailsub\Service\NotificationService
     * @inject
     */
    protected $notificationService;

    /**
     * subscribe
     *
     * @return void
     */
    public function subscribeAction()
    {
        $test = $this->addressRepository->findAll();
        var_dump(count($test));
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
        if ($this->settings['doubleOptIn']) {
            $address->setHidden(true);
        }
        $this->addressRepository->add($address);
        $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
        $persistenceManager->persistAll();
        $uid = $address->getUid();

        // @todo Send E-Mail with confirmation link if configured (should also contain a configurable validity and the UID of record)
        $body = $this->notificationService->getNotificationContent($uid);
        return $this->emailService->sendEmailMessage('asc@hoch2.de', 'asc@hoch2.de', 'Test', $body);

        // @todo params sender, receiver, subject

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
        // @todo THA: Security checks

        /* @var $address \Hochzwei\H2dmailsub\Domain\Model\Address */
        $address = $this->addressRepository->findAddressByUid($subscriptionUid);

        // @todo Evaluate if address is hidden and if not, show message that subscription is confirmed

        $address->setHidden(false);
        $this->addressRepository->update($address);

        // @todo Implement method
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
            var_dump('Send link');
        }

        // @todo: show message
    }


}