<?php

/**
 * @file
 */

require_once 'rerouteemail.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function rerouteemail_civicrm_config(&$config): void {
  _rerouteemail_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function rerouteemail_civicrm_install(): void {
  _rerouteemail_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function rerouteemail_civicrm_enable(): void {
  _rerouteemail_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_alterMailParams().
 */
function rerouteemail_civicrm_alterMailParams(&$params) {
  // Nothing to do if reroute is not enabled.
  $domainID = CRM_Core_Config::domainID();
  $settings = Civi::settings($domainID);
  if (!$settings->get('rerouteemail_enable')) {
    return;
  }

  $emailAddress = trim($settings->get('rerouteemail_email'));

  // Nothing to do if reroute email is not set.
  if (empty($emailAddress)) {
    return;
  }

  $actualRecipient = $params['toEmail'];

  // Set the reroute email address as the destination email.
  $params['toEmail'] = $emailAddress;

  // Add the reroute information in the log.
  $log = \CRM_Rerouteemail_ExtensionUtil::ts('Actual recipient was: %1. Email rerouted to %2}', [
    1 => $actualRecipient,
    2 => $emailAddress,
  ]);
  \Civi::log('reroute')->notice($log);

  // Show message at the top of the rerouted email.
  $msg = \CRM_Rerouteemail_ExtensionUtil::ts("This email was rerouted.") . "\n";
  $msg .= \CRM_Rerouteemail_ExtensionUtil::ts("Web site: %1", [1 => CIVICRM_UF_BASEURL]) . "\n";
  $msg .= \CRM_Rerouteemail_ExtensionUtil::ts("Rerouted to: %1", [1 => $emailAddress]) . "\n";
  $msg .= \CRM_Rerouteemail_ExtensionUtil::ts("Originally to: %1", [1 => $actualRecipient]) . "\n";

  // Suppress Cc emails and add the emails to the top message.
  if (!empty($params['cc'])) {
    $msg .= \CRM_Rerouteemail_ExtensionUtil::ts("Original CC: %1", [1 => $params['cc']]) . "\n";
    unset($params['cc']);
  }

  // Suppress Bcc emails and add the emails to the top message.
  if (!empty($params['bcc'])) {
    $msg .= \CRM_Rerouteemail_ExtensionUtil::ts("Original BCC: %1", [1 => $params['bcc']]) . "\n";
    unset($params['bcc']);
  }

  $msg .= "==================================================\n\n";

  // Prepare the text formatted email.
  $params['text'] = $msg . $params['text'];

  // Prepare the HTML formatted email.
  $params['html'] = nl2br($msg) . $params['html'];
  \Civi::log('reroute')->notice($msg);
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
function rerouteemail_civicrm_navigationMenu(&$menu) {
  _rerouteemail_civix_insert_navigation_menu($menu, 'Administer/System Settings', [
    'label' => \CRM_Rerouteemail_ExtensionUtil::ts('Reroute Email'),
    'name' => 'rerouteemail_setting',
    'url' => CRM_Utils_System::url('civicrm/admin/rerouteemail', 'reset=1', TRUE),
    'permission' => 'administer CiviCRM',
    'operator' => 'OR',
    'separator' => 0,
  ]);
  _rerouteemail_civix_navigationMenu($menu);
}
