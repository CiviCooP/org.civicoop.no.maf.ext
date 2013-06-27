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
  return _custom_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 */
function custom_civicrm_uninstall() {
	_custom_delete_group('maf_norway_individual');
	_custom_delete_group('maf_norway_organization');
	_custom_delete_group('maf_norway_contributions');
	_custom_delete_group('maf_norway_original_kid');
}

/**
 * Implementation of hook_civicrm_enable
 */
function custom_civicrm_enable() {

  _custom_add_field('maf_norway_individual', 'MAF Norway', 'Individual', 'NO_SocialSecurityNo', 'Fødselsnr', 'String', 'Text', '1');
  _custom_add_field('maf_norway_organization', 'MAF Norway', 'Organization', 'Organisasjonsnummer', 'Organisasjonsnummer', 'String', 'Text', '1');
  _custom_add_field('maf_norway_contributions', 'MAF Norway', 'Contribution', 'Aksjon ID', 'Aksjon ID', 'String', 'Text', '1');
  _custom_add_field('maf_norway_original_kid', 'Original values for KID', 'Contribution', 'Aktivitet ID', 'Aktivitet ID', 'String', 'Text', '1');
  _custom_add_field('maf_norway_original_kid', 'Original values for KID', 'Contribution', 'Orgininal contact ID', 'Orgininal contact ID', 'String', 'Text', '1');

  _custom_enable_group('maf_norway_individual', true);
  _custom_enable_group('maf_norway_organization', true);
  _custom_enable_group('maf_norway_contributions', true);
  _custom_enable_group('maf_norway_original_kid', true);
  
  return _custom_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 */
function custom_civicrm_disable() {

  _custom_enable_group('maf_norway_individual', false);
  _custom_enable_group('maf_norway_organization', false);
  _custom_enable_group('maf_norway_contributions', false);
  _custom_enable_group('maf_norway_original_kid', false);
  
  return _custom_civix_civicrm_disable();
}

function _custom_add_field($group, $group_title, $extends, $name, $label, $data_type, $html_type, $active) {
	$params['version']  = 3;
	$params['name'] = $group;
	$result = civicrm_api('CustomGroup', 'getsingle', $params);
	if (!isset($result['id'])) {
		unset($params);
		$params['version']  = 3;
		$params['name'] = $group;
		$params['title'] = $group_title;
		$params['extends'] = $extends;
		$params['is_active'] = '0';
		$result = civicrm_api('CustomGroup', 'create', $params);
	}
	$gid = false;
	if (isset($result['id'])) {
		$gid = $result['id'];
	}
	
	if ($gid) {
		unset($params);
		$params['version']  = 3;
		$params['custom_group_id'] = $gid;
		$params['label'] = $label;
		$result = civicrm_api('CustomField', 'getsingle', $params);
		if (!isset($result['id'])) {
			unset($params);
			$params['version']  = 3;
			$params['custom_group_id'] = $gid;
			$params['name'] = $name;
			$params['label'] = $label;
			$params['html_type'] = $html_type;
			$params['data_type'] = $data_type;
			$params['is_active'] = $active;
			$result = civicrm_api('CustomField', 'create', $params);
		}
	}
}

function _custom_delete_group($name) {
	$params['version']  = 3;
	$params['name'] = $name;
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
}

function _custom_enable_group($name, $enable) {
  $params['version']  = 3;
  $params['name'] = $name;
  $result = civicrm_api('CustomGroup', 'getsingle', $params);
  if (isset($result['id'])) {
	$gid = $result['id'];
	unset($params);
	$params['version']  = 3;
	$params['id'] = $gid;
	$params['is_active'] = $enable ? '1' : '0';
	$result = civicrm_api('CustomGroup', 'update', $params);
  }
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
