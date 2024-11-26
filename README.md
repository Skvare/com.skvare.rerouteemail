# com.skvare.rerouteemail

![Screenshot](/images/screenshot.png)

CiviCRM Reroute Email is an extension providing configuration to reroute all CiviCRM transactional emails and mailings to a different email address. Dev/staging instances often use this extension for testing purposes.


This is an [extension for CiviCRM](https://docs.civicrm.org/sysadmin/en/latest/customize/extensions/), licensed under [AGPL-3.0](LICENSE.txt).

## Getting Started

* Visit for configuration: http://domain.name/civicrm/admin/rerouteemail
* Enabled the setting and put an email address. Now each email sent by civicrm is received by this email address.

## Override settings

Override through `civicrm.settings.php` file.

```php
$civicrm_setting['Reroute Extension Settings']['rerouteemail_enable'] = 1;
$civicrm_setting['Reroute Extension Settings']['rerouteemail_email'] = 'override@youdomain.com';
```
