<?php
/*
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC. All rights reserved.                        |
 |                                                                    |
 | This work is published under the GNU AGPLv3 license with some      |
 | permitted exceptions and without any warranty. For full license    |
 | and copyright information, see https://civicrm.org/licensing       |
 +--------------------------------------------------------------------+
 */

use CRM_Rerouteemail_ExtensionUtil as E;

return [
  'rerouteemail_enable' => [
    'group_name' => 'Reroute Extension Settings',
    'name' => 'rerouteemail_enable',
    'group' => 'com.skvare.rerouteemail',
    'type' => 'Boolean',
    'html_type' => 'checkbox',
    'default' => 0,
    'is_domain' => 1,
    'is_contact' => 0,
    'title' => E::ts('Reroute Enabled?'),
    'description' => E::ts('Check if you want to enable reroute the emails.'),
    'html_attributes' => [],
    'settings_pages' => [
      'stripe' => [
        'weight' => 10,
      ],
    ],
  ],
  'rerouteemail_email' => [
    'group_name' => 'Reroute Extension Settings',
    'name' => 'rerouteemail_email',
    'group' => 'com.skvare.rerouteemail',
    'type' => 'String',
    'html_type' => 'text',
    'default' => '',
    'is_domain' => 1,
    'is_contact' => 0,
    'title' => E::ts('Email Address'),
    'description' => E::ts('Reroute email address.'),
    'html_attributes' => [
      'size' => 30,
      'maxlength' => 22,
    ],
    'settings_pages' => [
      'stripe' => [
        'weight' => 25,
      ],
    ],
  ],
];
