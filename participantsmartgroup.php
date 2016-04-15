<?php

require_once 'participantsmartgroup.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function participantsmartgroup_civicrm_config(&$config) {
  _participantsmartgroup_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function participantsmartgroup_civicrm_xmlMenu(&$files) {
  _participantsmartgroup_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function participantsmartgroup_civicrm_install() {
  _participantsmartgroup_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function participantsmartgroup_civicrm_uninstall() {
  _participantsmartgroup_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function participantsmartgroup_civicrm_enable() {
  _participantsmartgroup_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function participantsmartgroup_civicrm_disable() {
  _participantsmartgroup_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function participantsmartgroup_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _participantsmartgroup_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function participantsmartgroup_civicrm_managed(&$entities) {
  _participantsmartgroup_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function participantsmartgroup_civicrm_caseTypes(&$caseTypes) {
  _participantsmartgroup_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function participantsmartgroup_civicrm_angularModules(&$angularModules) {
_participantsmartgroup_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function participantsmartgroup_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _participantsmartgroup_civix_civicrm_alterSettingsFolders($metaDataFolders);
}


/**
 * Implements hook_civicrm_post().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_post
 *
 **/
function participantsmartgroup_civicrm_post( $op, $objectName, $objectId, &$objectRef ) {

  if($objectName == 'Event' && $op == 'create') {
    $smarty =  CRM_Core_Smarty::singleton( );
    $values = $smarty->get_template_vars( );
    if(!isset($values['create_smartgroup']) || !$values['create_smartgroup']) {
      return;
    }

    //Creating Smart Group with the help of Saved Search API
    $smartGroupName  = str_replace(' ', '_', $objectRef->title)."-Registrants";
    $smartGroupTitle = $objectRef->title." Registrants";

    $grpExists = civicrm_api('Group', 'get', array('version' => 3,'name' => $smartGroupName));

      if($grpExists['count'] > 0) {
        $smartGroupName  = str_replace(' ', '_', $objectRef->title)."-Registrants_".uniqid();
        $smartGroupTitle = $objectRef->title." Registrants_".uniqid();
        $session->setStatus("Smart group created with name : {$smartGroupTitle}",'Registered Smart Group','info');
      }

    $params = array(
      'form_values' => array(
        // Participants for events
        'event_id'              => $objectId,
        'participant_status_id' => 1,
        'participant_test'      => 0
      ),
      'api.Group.create'  => array(
        'name'            => $smartGroupName,  //Setting event title as group name
        'title'           => $smartGroupTitle, //Setting event title as group title
        'description'     => "Registered Participants for {$objectRef->title} event",
        'saved_search_id' => '$value.id',
        'is_active'       => 1,
        'visibility'      => 'User and User Admin Only',
        'is_hidden'       => 0,
        'is_reserved'     => 0,
        'group_type'      => array('2' => 'Mailing List') //Setting Group Type to "Mailing List"
      ),
    );

    //Creating Participant 'Saved Search' and creating 'Smart Group' for event
    civicrm_api3('SavedSearch', 'create', $params);
  }
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 *
 **/
function participantsmartgroup_civicrm_buildForm($formName, &$form) {

  //Adding "Create Participant Smart Group" checkbox on 'Add Event' form
  if($formName == 'CRM_Event_Form_ManageEvent_EventInfo' && $form->getVar('_action') == CRM_Core_Action::ADD ) {
    $form->addElement('checkbox', 'create_smartgroup', ts('Create Registered Participant Smart Group'), NULL, array('checked' => TRUE));
  }
}


/**
 * Implements hook_civicrm_pre().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_pre
 *
 **/
function participantsmartgroup_civicrm_pre($op, $objectName, $id, &$params) {

  //Adding smart group create variable in smarty scope.
  if($objectName == 'Event' && $op == 'create') {
    if(isset($params['create_smartgroup']) && $params['create_smartgroup']) {
      $smarty =  CRM_Core_Smarty::singleton( );
      $smarty->pushScope(array('create_smartgroup' => TRUE));
    }
  }
}

