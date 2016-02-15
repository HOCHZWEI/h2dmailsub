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
        $this->view->assign('uid',$address->getUid());

        // @todo Send E-Mail with confirmation link if configured (should also contain a configurable validity and the UID of record)
        return $this->emailService->sendEmailMessage();

        // @todo Show message (Subscription Saved / Subscription saved but needs to be confirmed)
    }

    /**
     * confirm a subscription
     *
     * @param int $uid
     * @return void
     */
    public function confirmSubscriptionAction($uid)
    {
        $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $querySettings->setRespectStoragePage(false);
        $querySettings->setIgnoreEnableFields(true);
        $this->addressRepository->setDefaultQuerySettings($querySettings);

        /* @var $address \Hochzwei\H2dmailsub\Domain\Model\Address */
        $address = $this->addressRepository->findOneByUid($uid);

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
}