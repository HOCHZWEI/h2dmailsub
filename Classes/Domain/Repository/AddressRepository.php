<?php
namespace Hochzwei\H2dmailsub\Domain\Repository;

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

/**
 * The repository for the domain model Address
 */
class AddressRepository extends \TYPO3\TtAddress\Domain\Repository\AddressRepository
{

    /**
     * Returns one address (also hidden) record by the given uid
     *
     * @param int $uid
     * @return object
     */
    public function findAddressByUid($uid)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);
        $query->matching($query->equals('uid', $uid));
        return $query->execute()->getFirst();
    }

    /**
     * Returns one address (also hidden) record by the given email
     *
     * @param string $email
     * @return object
     */
    public function findAddressByEmail($email)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);
        $query->matching($query->equals('email', $email));
        return $query->execute()->getFirst();
    }

}
