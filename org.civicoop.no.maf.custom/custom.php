<?php
ini_set( 'display_errors', '1');
require_once 'custom.civix.php';

/**
 * Implementation of hook_civicrm_config
 */
function custom_civicrm_config(&$config) {
  _custom_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 */
function custom_civicrm_xmlMenu(&$files) {
  _custom_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 */
function custom_civicrm_install() {
  /** 
   * Create specific fields
   */
  $params['version']  = 3;
  $params['name'] = 'maf_norway_individual';
  $result = civicrm_api('CustomGroup', 'getsingle', $params);
  if (!isset($result['id'])) {
	unset($params);
	$params['version']  = 3;
	$params['name'] = 'maf_norway_individual';
	$params['title'] = 'MAF Norway';
	$params['extends'] = 'Individual';
	$params['is_active'] = '0';
	$result = civicrm_api('CustomGroup', 'create', $params);
  }
  if (isset($result['id'])) {
	unset($params);
	$params['version']  = 3;
	$params['custom_group_id'] = $result['id'];
	$params['name'] = 'NO_SocialSecurityNo';
	$params['label'] = 'Fødselsnr';
	$params['is_active'] = '1';
	$result = civicrm_api('CustomField', 'create', $params);
  }
  return _custom_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 */
function custom_civicrm_uninstall() {
  $params['version']  = 3;
  $params['name'] = 'maf_norway_individual';
  $result = civicrm_api('CustomGroup', 'getsingle', $params);
  if (isset($result['id'])) {
	$gid = $result['id'];
	unset($params);
	$params['version']  = 3;
	$params['custom_group_id'] = $gid;
	$result = civicrm_api('CustomField', 'get', $params);
	if (isset($result['values']) && is_array($result['values'])) {
		foreach($result['values']  as $field) {
			unset($params);
			$params['version']  = 3;
			$params['id'] = $field['id'];
			civicrm_api('CustomField', 'delete', $params);
		}
	}
	
	unset($params);
	$params['version']  = 3;
	$params['id'] = $gid;
	$result = civicrm_api('CustomGroup', 'delete', $params);
  }
  return _custom_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 */
function custom_civicrm_enable() {
  $params['version']  = 3;
  $params['name'] = 'maf_norway_individual';
  $result = civicrm_api('CustomGroup', 'getsingle', $params);
  if (isset($result['id'])) {
	$gid = $result['id'];
	unset($params);
	$params['version']  = 3;
	$params['id'] = $gid;
	$params['is_active'] = '1';
	$result = civicrm_api('CustomGroup', 'update', $params);
  }
  return _custom_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 */
function custom_civicrm_disable() {
  $params['version']  = 3;
  $params['name'] = 'maf_norway_individual';
  $result = civicrm_api('CustomGroup', 'getsingle', $params);
  if (isset($result['id'])) {
	$gid = $result['id'];
	unset($params);
	$params['version']  = 3;
	$params['id'] = $gid;
	$params['is_active'] = '0';
	$result = civicrm_api('CustomGroup', 'update', $params);
  }
  return _custom_civix_civicrm_disable();
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
function custom_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _custom_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 */
function custom_civicrm_managed(&$entities) {
  return _custom_civix_civicrm_managed($entities);
}
