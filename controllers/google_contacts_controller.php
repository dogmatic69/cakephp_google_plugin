<?php
/**
* Google Api Base Class
*
* Methods used to interact with Google Api
*
* Copyright (c) 2009 Juan Carlos del Valle ( imekinox )
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @copyright Copyright (c) 2009 Juan Carlos del Valle ( imekinox )
* @link http://www.imekinox.com
* @package google
* @subpackage google.controllers.GoogleContactsController
* @license MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

/**
* Google Contacts Controller for testing Datasource
*
* Configuration in app/config/database.php:
*
* var $google_contacts = array(
*     'datasource'  => 'google_contacts',
*     'accountType' => 'GOOGLE',
*     'Email'       => 'YOUR GOOGLE EMAIL',
*     'Passwd'      => 'YOUR PASSWORD',
*     'source'      => 'companyName-applicationName-versionID',
*     'database'    => ''
* );
*
* Find methods:
* - all
* - count
* - first
*
* Default limit is 25 use limit for override.
*
* Available conditions:
* - start-index -> For paging
* - updated-min -> The lower bound on entry update dates.
* - orderby     -> Sorting criterion. The only supported value is lastmodified.
* - showdeleted -> Include deleted contacts in the returned contacts feed.
* - group       -> Value of this parameter specifies group ID
*
* Restult array keys ( required ):
* - etag
* - contact_id
* - updated
* - edited
* - category
* - title
*
* Restult array keys ( optional ):
* - content
* - name
* - nickname
* - birthday
* - organization
* - email
* - im
* - phones
* - address
* - events
* - relations
* - custom
* - websites
* - groups
*
* @todo could be called ContactsController?
*/

class GoogleContactsController extends AppController
{
  var $name = 'GoogleContacts';
  var $uses = array( 'GoogleContacts' );

  function index()
  {
    $res = $this->GoogleContacts->find(
             'all',
             array(
               'limit' => '2'
             )
           );
    $contact = $res[0];
    $this->GoogleContacts->create($contact);
    $this->GoogleContacts->save();
  }
}