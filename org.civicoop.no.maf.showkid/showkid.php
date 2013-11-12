<?php

require_once 'showkid.civix.php';

function showkid_civicrm_searchColumns($objectName, &$headers,  &$values, &$selector ) {
	if ($objectName == 'contribution') {
		foreach($headers as $hid => $header) {
			if (isset($header['sort']) && $header['sort'] == 'product_name') {
				$headers[$hid]['name']  = 'KID';
			}
		}
		foreach($values as $id => $value) {
			$values[$id]['product_name'] = kid_number_lookup('Contribution', $value['contribution_id']);
		}
	}
}


/**
 * Implementation of hook_civicrm_config
 */
function showkid_civicrm_config(&$config) {
  _showkid_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 */
function showkid_civicrm_xmlMenu(&$files) {
  _showkid_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 */
function showkid_civicrm_install() {
  return _showkid_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 */
function showkid_civicrm_uninstall() {
  return _showkid_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 */
function showkid_civicrm_enable() {	
	return _showkid_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 */
function showkid_civicrm_disable() {	
	return _showkid_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 */
function showkid_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _showkid_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 */
function showkid_civicrm_managed(&$entities) {
  return _showkid_civix_civicrm_managed($entities);
}
