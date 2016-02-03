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

    }

    /**
     * safe subscribe
     *
     * @param \TYPO3\TtAddress\Domain\Model\Address $address
     * @return void
     */
    public function saveSubscriptionAction(\TYPO3\TtAddress\Domain\Model\Address $address)
    {
        $this->addressRepository->add($address);
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