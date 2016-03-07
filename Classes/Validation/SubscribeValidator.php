<?php
namespace Hochzwei\H2dmailsub\Validation;

use Hochzwei\H2dmailsub\Domain\Model\Address;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * SubscribeValidator
 *
 */
class SubscribeValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
    /**
     * The configurationManager
     *
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager
     * @inject
     */
    protected $configurationManager;

    /**
     * Object Manager
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     * @inject
     */
    protected $objectManager;

    /**
     * @param Address $address
     * @return bool
     */
    protected function isValid($address)
    {
        $result = true;
        $settings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'H2dmailsub',
            'Pidmailsubscribe'
        );
        $validationSettings = $settings['validation'];
        foreach ($validationSettings as $field => $validations) {
            $value = $address->{'get' . ucfirst($field)}();
            foreach ($validations as $validation => $validationSetting) {
                switch ($validation) {
                    case 'required':
                        if ($validationSetting === '1' && empty($value)) {
                            $error = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Validation\Error',
                                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('validation.required', 'h2dmailsub'), time());
                            $this->result->forProperty($field)->addError($error);
                            $result = false;
                        }
                        break;
                    case 'email':
                        if (!empty($value) && $validationSetting === '1' && !\TYPO3\CMS\Core\Utility\GeneralUtility::validEmail($value)) {
                            $error = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Validation\Error',
                                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('validation.email', 'h2dmailsub'), time());
                            $this->result->forProperty($field)->addError($error);
                            $result = false;
                        }
                        break;
                }
            }
        }
        return $result;
    }
}
