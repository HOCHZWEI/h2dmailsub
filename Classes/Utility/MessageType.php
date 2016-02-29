<?php
namespace Hochzwei\H2dmailsub\Utility;

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
 * MessageType
 */
class MessageType
{
    const SUBSCRIPTION_CONFIRM = 0;
    const SUBSCRIPTION_CONFIRMED = 1;
    const SUBSCRIPTION_UNSUBSCRIBE = 2;
}