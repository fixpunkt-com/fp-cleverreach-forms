<?php

namespace Fixpunkt\FpCleverreachForms\Domain\Finishers;

use Fixpunkt\FpCleverreach\Utility\Connector;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Form\Domain\Finishers\AbstractFinisher;

class CleverreachFinisher extends AbstractFinisher
{

    /**
     * @var array
     */
    protected $defaultOptions = [
        'formId' => -1,
        'groupId' => -1,
        'active' => false,
        'email' => '',
        'custom' => []
    ];

    protected function executeInternal(): void {
        $active = (bool)$this->parseOption('active');
        if(!$active) return;

        $silent = true; // (bool)$this->parseOption('silent');
        $email = (string)$this->parseOption('email');
        $formId = (int)$this->parseOption('formId');
        $groupId = (int)$this->parseOption('groupId');

        if(!trim($email)) {
            $this->handleError(new \Exception("E-Mail Adresse ist Leer!"), "E-Mail Adresse ist Leer!", $silent);
            return;
        }

        $options = $this->parseOption('custom') ?: [];
        $options = array_flip($options);

        /** @var Connector $cleverreach */
        $cleverreach = GeneralUtility::makeInstance(Connector::class);
        $error = null;
        $errorMsg = null;

        try {
            $cleverreach->addPerson(
                $email,
                $options,
                $groupId,
                $formId,
                false
            ); // returned immer true!
        } catch(\Exception $err) {
            $error = $err;
            $errorMsg = "Fehler beim hinzufügen zu CleverReach!";
        }

        if($error != null) {
            $this->handleError($error, $errorMsg, $silent);
        }
    }

    protected function handleError(?\Exception $error, ?string $errorMsg, $silent=true) {
        if($error == null) return;

        trigger_error($errorMsg . ": " . $error->getMessage() . " in File " . $error->getFile() . " : " . $error->getLine(), E_USER_WARNING);

        if($silent) return;

        throw $error;

        // TODO: Show Error Page?
    }
}