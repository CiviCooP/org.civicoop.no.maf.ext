<?php
require_once 'maf_tokens.civix.php';

/**
 * Implementation of hook_civicrm_config
 */
function maf_tokens_civicrm_config(&$config) {
  _maf_tokens_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 */
function maf_tokens_civicrm_xmlMenu(&$files) {
  _maf_tokens_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 */
function maf_tokens_civicrm_install() {  
  return _maf_tokens_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 */
function maf_tokens_civicrm_uninstall() {
	return _maf_tokens_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 */
function maf_tokens_civicrm_enable() {	  
  return _maf_tokens_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 */
function maf_tokens_civicrm_disable() {  
  return _maf_tokens_civix_civicrm_disable();
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
function maf_tokens_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _maf_tokens_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 */
function maf_tokens_civicrm_managed(&$entities) {
  return _maf_tokens_civix_civicrm_managed($entities);
}

function maf_tokens_civicrm_tokens(&$tokens) {
  $tokens['maf_tokens'] = array(
  'maf_tokens.today' => 'Todays date',
	'maf_tokens.lastcontribution_amount' => 'Amount of last contribution',
	'maf_tokens.lastcontribution_date' => 'Date of last contribution',
	'maf_tokens.lastcontribution_financial_type' => 'Financial type of last contribution',
  'maf_tokens.pronoun_dudere' => 'Pronoun du/dere',
  'maf_tokens.pronoun_dudere_capital' => 'Pronoun Du/Dere',
  'maf_tokens.pronoun_degdere' => 'Pronoun deg/dere',
  'maf_tokens.pronoun_degdere_capital' => 'Pronoun Deg/Dere',
  'maf_tokens.pronoun_dinderes' => 'Pronoun din/deres',
  'maf_tokens.pronoun_dinderes_capital' => 'Pronoun Din/Deres',
  'maf_tokens.pronoun_dinederes' => 'Pronoun dine/deres',
  'maf_tokens.pronoun_dinederes_capital' => 'Pronoun Dine/Deres',
    
  );
}

/**
 * implementation of hook_civicrm_tokenValues
 * 
 * This function deletegates the tokens to the desired functions
 * 
 * @param type $values
 * @param type $cids
 * @param type $job
 * @param type $tokens
 * @param type $context
 */
function maf_tokens_civicrm_tokenValues(&$values, $cids, $job = null, $tokens = array(), $context = null) {
  if (!empty($tokens['maf_tokens'])) {
    //token maf_tokens.today
		if (in_array('today', $tokens['maf_tokens'])) {
			maf_tokens_today($values, $cids, $job, $tokens,$context);
		}
		//tokens:
    // - maf_tokens.lastcontribution_amount
    // - maf_tokens.lastcontribution_date
    // - maf_tokens.lastcontribution_financial_type
		if ((in_array('lastcontribution_amount', $tokens['maf_tokens'])) || (in_array('lastcontribution_date', $tokens['maf_tokens'])) || (in_array('lastcontribution_financial_type', $tokens['maf_tokens']))) {
      maf_tokens_lastcontribution($values, $cids, $job, $tokens,$context);
		}
    
    //pronoun tokens
    //
    // We want to say things like this:
    // «Thanks for your support, Jaap» or «Thanks for your support, Jaap and Erik»
    // In Nowegian that would be:
    // «Takk for din støtte, Jaap» eller «Takk for deres støtte, Jaap og Erik»
    // (And we want to use the different personal pronouns MANY times in the same letter)
    if (in_array('pronoun_dudere', $tokens['maf_tokens']) ||
        in_array('pronoun_dudere_capital', $tokens['maf_tokens']) ||
        in_array('pronoun_degdere', $tokens['maf_tokens']) ||
        in_array('pronoun_degdere_capital', $tokens['maf_tokens']) ||
        in_array('pronoun_dinderes', $tokens['maf_tokens']) ||
        in_array('pronoun_dinderes_capital', $tokens['maf_tokens']) ||
        in_array('pronoun_dinederes', $tokens['maf_tokens']) ||
        in_array('pronoun_dinederes_capital', $tokens['maf_tokens'])
        ) {
      maf_tokens_pronouns($values, $cids, $job, $tokens,$context);
    }
	}
}

function maf_tokens_pronouns(&$values, $cids, $job = null, $tokens = array(), $context = null) {
  	$contacts = implode(',', $cids);
    if (in_array('pronoun_dudere', $tokens['maf_tokens']) ||
        in_array('pronoun_dudere_capital', $tokens['maf_tokens']) ||
        in_array('pronoun_degdere', $tokens['maf_tokens']) ||
        in_array('pronoun_degdere_capital', $tokens['maf_tokens']) ||
        in_array('pronoun_dinderes', $tokens['maf_tokens']) ||
        in_array('pronoun_dinderes_capital', $tokens['maf_tokens']) ||
        in_array('pronoun_dinederes', $tokens['maf_tokens']) ||
        in_array('pronoun_dinederes_capital', $tokens['maf_tokens'])
        ) {
      
      
      $dao = CRM_Core_DAO::executeQuery("SELECT * FROM `civicrm_contact` WHERE `id` IN (".$contacts.");");
      while ($dao->fetch()) {
        $cid = $dao->id;
				if (in_array($cid, $cids)) {
          if ($dao->contact_type == "Individual") {
            if (in_array('pronoun_dudere', $tokens['maf_tokens'])) {
              $values[$cid]['maf_tokens.pronoun_dudere'] = "du";
            }
            if (in_array('pronoun_dudere_capital', $tokens['maf_tokens'])) {
              $values[$cid]['maf_tokens.pronoun_dudere_capital'] = "Du";
            }            
            if (in_array('pronoun_degdere', $tokens['maf_tokens'])) {
              $values[$cid]['maf_tokens.pronoun_degdere'] = "deg";
            }
            if (in_array('pronoun_degdere_capital', $tokens['maf_tokens'])) {
              $values[$cid]['maf_tokens.pronoun_degdere_capital'] = "Deg";
            }
            if (in_array('pronoun_dinderes', $tokens['maf_tokens'])) {
              $values[$cid]['maf_tokens.pronoun_dinderes'] = "din";
            }
            if (in_array('pronoun_dinderes_capital', $tokens['maf_tokens'])) {
              $values[$cid]['maf_tokens.pronoun_dinderes_capital'] = "Din";
            }
            if (in_array('pronoun_dinederes', $tokens['maf_tokens'])) {
              $values[$cid]['maf_tokens.pronoun_dinederes'] = "dine";
            }
            if (in_array('pronoun_dinederes_capital', $tokens['maf_tokens'])) {
              $values[$cid]['maf_tokens.pronoun_dudere_capital'] = "Dine";
            }
            
          } else {
            if (in_array('pronoun_dudere', $tokens['maf_tokens'])) {
              $values[$cid]['maf_tokens.pronoun_dudere'] = "dere";
            }
            if (in_array('pronoun_dudere_capital', $tokens['maf_tokens'])) {
              $values[$cid]['maf_tokens.pronoun_dudere_capital'] = "Dere";
            }
            if (in_array('pronoun_degdere', $tokens['maf_tokens'])) {
              $values[$cid]['maf_tokens.pronoun_degdere'] = "dere";
            }
            if (in_array('pronoun_degdere_capital', $tokens['maf_tokens'])) {
              $values[$cid]['maf_tokens.pronoun_degdere_capital'] = "Dere";
            }
            if (in_array('pronoun_dinderes', $tokens['maf_tokens'])) {
              $values[$cid]['maf_tokens.pronoun_dinderes'] = "deres";
            }
            if (in_array('pronoun_dinderes_capital', $tokens['maf_tokens'])) {
              $values[$cid]['maf_tokens.pronoun_dinderes_capital'] = "Deres";
            }
            if (in_array('pronoun_dinederes', $tokens['maf_tokens'])) {
              $values[$cid]['maf_tokens.pronoun_dinederes'] = "deres";
            }
            if (in_array('pronoun_dinederes_capital', $tokens['maf_tokens'])) {
              $values[$cid]['maf_tokens.pronoun_dudere_capital'] = "Deres";
            }
          }
        }
      }
    }
}


/*
 * Returns the value of tokens:
 * - maf_tokens.lastcontribution_amount
 * - maf_tokens.lastcontribution_date
 * - maf_tokens.lastcontribution_financial_type
 */
function maf_tokens_lastcontribution(&$values, $cids, $job = null, $tokens = array(), $context = null) {
  $contacts = implode(',', $cids);

	if (!empty($tokens['maf_tokens'])) {		
		if ((in_array('lastcontribution_amount', $tokens['maf_tokens'])) || (in_array('lastcontribution_date', $tokens['maf_tokens'])) || (in_array('lastcontribution_financial_type', $tokens['maf_tokens']))) {
			$dao = &CRM_Core_DAO::executeQuery("
				SELECT cc.*, ft.name as financial_type
				FROM civicrm_contribution as cc LEFT JOIN civicrm_financial_type ft ON cc.financial_type_id = ft.id
				WHERE cc.is_test = 0 AND 
				receive_date = (SELECT max(receive_date) FROM civicrm_contribution c2 WHERE c2.contact_id = cc.contact_id AND c2.contribution_status_id = 1)
				AND cc.contact_id IN (".$contacts.")
			");
			
			while ($dao->fetch()) {
				$cid = $dao->contact_id;
				if (in_array($cid, $cids)) {
					if (in_array('lastcontribution_amount', $tokens['maf_tokens'])) {
						$amount = (float) $dao->total_amount;
						$values[$cid]['maf_tokens.lastcontribution_amount'] = _maf_tokens_money_format($amount);
					}
					if (in_array('lastcontribution_date', $tokens['maf_tokens'])) {
						$date = new DateTime($dao->receive_date);
						$values[$cid]['maf_tokens.lastcontribution_date'] = _maf_tokens_date_format($date);
					}
					if (in_array('lastcontribution_financial_type', $tokens['maf_tokens'])) {
						$values[$cid]['maf_tokens.lastcontribution_financial_type'] = $dao->financial_type;
					}
				}
			}
		}
	}
}

/*
 * Returns the value of token maf_tokens.today
 */
function maf_tokens_today(&$values, $cids, $job = null, $tokens = array(), $context = null) {
  if (!empty($tokens['maf_tokens'])) {
		if (in_array('today', $tokens['maf_tokens'])) {
			$today = new DateTime();
			foreach ($cids as $cid) {
				$values[$cid]['maf_tokens.today'] = _maf_tokens_date_format($today);
			}
		}
  }
}


/**
 * Fomat a number as NOK money format
 * 
 * @param type $amount
 * @return type
 */
function _maf_tokens_money_format($amount) {
	$rep = array(
		'.' => ',',
		',' => ' ',
	);
	
	$hasDecimals = false;
	$rest = fmod($amount, 1);
	if ($rest > 0.00) {
		$hasDecimals = true;
	}
	if ($hasDecimals) {
		$amount = number_format($amount, 2, '.', ',');
	} else {
		$amount = number_format($amount, 0, '.', ',');
	}
	$amount = strtr($amount, $rep);
	return $amount;
}

/**
 * Format a date to norwegian style
 * 
 * @param type $date
 * @return string
 */
function _maf_tokens_date_format($date) {
	$months = array (
		'1' => 'Januar',
		'2' =>'Februar', 
		'3' =>'Mars', 
		'4' =>'April', 
		'5' =>'Mai', 
		'6' =>'Juni',
		'7' =>'Juli',
		'8' =>'August',
		'9' =>'September',
		'10' =>'Oktober',
		'11' =>'November',
		'12' =>'Desember',
	);
	
	$month_nr = $date->format('n');
	$month = '';
	if (isset($months[$month_nr])) {
		$month = $months[$month_nr];
	}
	$str = $date->format('j').'. '.$month.' '.$date->format('Y');
	return $str;
}
