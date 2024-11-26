<?php

use CRM_Rerouteemail_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Rerouteemail_Form_Setting extends CRM_Admin_Form_Setting {

  /**
   * @throws \CRM_Core_Exception
   */
  public function buildQuickForm(): void {
    $this->add('advcheckbox', 'rerouteemail_enable', ts('Enabled?'));
    $this->add('text', 'rerouteemail_email', 'Email Address', ['size' => 60]);
    $this->addButtons([
      [
        'type' => 'submit',
        'name' => E::ts('Submit'),
        'isDefault' => TRUE,
      ],
    ]);

    // Export form elements.
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();

    // Use settings as defined in default domain.
    $domainID = CRM_Core_Config::domainID();
    $settings = Civi::settings($domainID);
    $setDefaults = [];
    foreach ($this->getRenderableElementNames() as $elementName) {
      $setDefaults[$elementName] = $settings->get($elementName);
    }
    $this->setDefaults($setDefaults);
    $this->addFormRule(['CRM_Rerouteemail_Form_Setting', 'formRule'], $this);
  }

  /**
   * Process the user submitted custom data values.
   *
   */
  public function postProcess(): void {
    $values = $this->exportValues();
    $domainID = CRM_Core_Config::domainID();
    $settings = Civi::settings($domainID);

    foreach ($values as $k => $v) {
      if (strpos($k, 'rerouteemail_') === 0) {
        $settings->set($k, $v);
      }
    }
    CRM_Core_Session::setStatus(E::ts('Setting updated successfully'));
  }

  /**
   * Global form rule.
   *
   * @param array $values
   *   The input form values.
   * @param array $files
   *   The uploaded files if any.
   * @param CRM_Core_Form $self
   *   The form object.
   *
   * @return bool|array
   *   true if no errors, else array of errors
   */
  public static function formRule($values, $files, $self) {
    $errors = [];
    if (!empty($values['rerouteemail_email']) && !filter_var($values['rerouteemail_email'], FILTER_VALIDATE_EMAIL)) {
      $errors['rerouteemail_email'] = ts('Invalid email address.');
    }
    elseif (!empty($values['rerouteemail_enable']) && empty($values['rerouteemail_email'])) {
      $errors['rerouteemail_email'] = ts('Email address is required.');
    }

    return empty($errors) ? TRUE : $errors;
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames(): array {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = [];
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

}
