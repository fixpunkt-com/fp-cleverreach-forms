<?php
defined('TYPO3') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'fp_cleverreach_forms',
    'Configuration/TypoScript',
    'Cleverreach Integration for EXT:form'
);
