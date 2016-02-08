<?php
namespace Hochzwei\H2dmailsub\Controller;

/**
 * AddressController
 *
 */
class AddressController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * addressRepository
     *
     * @var \TYPO3\TtAddress\Domain\Repository\AddressRepository
     * @inject
     */
    protected $addressRepository = null;

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
        // @todo Get UID of newly created record

        // @todo Send E-Mail with confirmation link if configured (should also contain a configurable validity and the UID of record)

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
        // @todo fix Bug $address->setHidden

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