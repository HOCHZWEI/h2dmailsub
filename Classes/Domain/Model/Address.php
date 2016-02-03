<?php
namespace Hochzwei\H2dmailsub\Domain\Model;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 HOCHZWEI <typo3@hoch2.de>, HOCHZWEI – büro für visuelle kommunikation gmbh & co. kg
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Address
 */
class Address extends \TYPO3\TtAddress\Domain\Model\Address
{

    /**
     * Record is hidden
     *
     * @var bool
     */
    protected $hidden;

    /**
     * Gender
     *
     * @var string
     */
    protected $localgender = '';
    
    /**
     * Returns the localgender
     *
     * @return string $localgender
     */
    public function getLocalgender()
    {
        return $this->localgender;
    }
    
    /**
     * Sets the localgender
     *
     * @param string $localgender
     * @return void
     */
    public function setLocalgender($localgender)
    {
        $this->localgender = $localgender;
    }

    /**
     * Returns if record is hidden
     *
     * @return bool
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Sets if record should be hidden
     *
     * @param bool $hidden
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

}