<?php
/**
 * COmanage Registry CO Identifier Assignments Controller
 *
 * Copyright (C) 2012 University Corporation for Advanced Internet Development, Inc.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 * 
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software distributed under
 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 *
 * @copyright     Copyright (C) 2012 University Corporation for Advanced Internet Development, Inc.
 * @link          http://www.internet2.edu/comanage COmanage Project
 * @package       registry
 * @since         COmanage Registry v0.6
 * @license       Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @version       $Id$
 */

App::uses("StandardController", "Controller");
  
class CoIdentifierAssignmentsController extends StandardController {
  // Class name, used by Cake
  public $name = "CoIdentifierAssignments";
  
  // When using additional models, we must also specify our own
//  public $uses = array('CoIdentifierAssignment', 'Identifier');
  
  // Establish pagination parameters for HTML views
  public $paginate = array(
    'limit' => 25,
    'order' => array(
      'CoIdentifierAssignment.type_name' => 'asc'
    )
  );
  
  // This controller needs a CO to be set
  public $requires_co = true;
  
  /**
   * Callback before other controller methods are invoked or views are rendered.
   * - postcondition: Redirect may be issued
   *
   * @since  COmanage Registry v0.6
   */
  
  function beforeFilter() {
    parent::beforeFilter();
    
    // Identifier supports Extended Types. Figure out what types are defined
    // in order for the views to render properly.
    
    $this->set('identifier_types', $this->Co->CoPerson->Identifier->types($this->cur_co['Co']['id']));
  }
  
  /**
   * Authorization for this Controller, called by Auth component
   * - precondition: Session.Auth holds data used for authz decisions
   * - postcondition: $permissions set with calculated permissions
   *
   * @since  COmanage Registry v0.6
   * @return Array Permissions
   */
  
  function isAuthorized() {
    $cmr = $this->calculateCMRoles();
    
    // Construct the permission set for this user, which will also be passed to the view.
    $p = array();
    
    // Determine what operations this user can perform
    
    // Add a new CO Identifier Assignment?
    $p['add'] = ($cmr['cmadmin'] || $cmr['coadmin']);
    
    // Delete an existing CO Identifier Assignment?
    $p['delete'] = ($cmr['cmadmin'] || $cmr['coadmin']);
    
    // Edit an existing CO Identifier Assignment?
    $p['edit'] = ($cmr['cmadmin'] || $cmr['coadmin']);
    
    // View all existing CO Identifier Assignments?
    $p['index'] = ($cmr['cmadmin'] || $cmr['coadmin']);
    
    // View an existing CO Identifier Assignment?
    $p['view'] = ($cmr['cmadmin'] || $cmr['coadmin']);

    $this->set('permissions', $p);
    return($p[$this->action]);
  }
}