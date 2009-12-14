<?php
class GoogleContactsController extends AppController {
  var $name = 'GoogleContacts';
  var $uses = array('GoogleContacts');

  function index() {

    /*

    Find methods (all,count, first).

    Default limit is 25 use limit for override.
    
    Available conditions:

      start-index -> For paging
      updated-min -> The lower bound on entry update dates.
      orderby -> Sorting criterion. The only supported value is lastmodified.
      showdeleted -> Include deleted contacts in the returned contacts feed.
      group -> Value of this parameter specifies group ID

    Restult array keys:
    
      gd$etag
      id
      updated
      app$edited
      category
      title
      content
      link
      gd$name
      gContact$birthday
      gd$organization
      gd$email
      gd$im
      gd$phoneNumber
      gd$structuredPostalAddress
      gContact$website
      gContact$groupMembershipInfo

    */
    
    $res = $this->GoogleContacts->find('all',array('limit'=>'50'));
    // $res = $this->GoogleContacts->findById('1027b9d98daaf832');
    debug($res);
  }

}
