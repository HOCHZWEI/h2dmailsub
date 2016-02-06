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

        // @todo Get UID of newly created record

        // @todo Send E-Mail with confirmation link if configured (should also contain a configurable validity and the UID of record)

        // @todo Show message (Subscription Saved / Subscription saved but needs to be confirmed)
    }

    public function confirmSubscriptionAction()
    {
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