<?php
/**
* Google Contacts Datasource
*
* Simplifies managing contacts with the google contacts api.
*
* Copyright (c) 2009 Juan Carlos del Valle ( imekinox )
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) 2009 Juan Carlos del Valle ( imekinox )
* @link http://www.imekinox.com
* @package       google
* @subpackage    google.models.datasources.google.google_contacts
* @license MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

App::import('Lib', 'GoogleApiBase');

/**
* GoogleContactsSource
*
* Datasource for Google Contacts
*/

class GoogleContactsSource extends DataSource {

  /**
  * Version for this Data Source.
  *
  * @var string
  * @access public
  */
  var $version = '0.1';

  /**
  * Description string for this Data Source.
  *
  * @var string
  * @access public
  */
  
  var $description = 'GoogleContacts Datasource';

  /**
  * Google api base class
  *
  * @var Object
  * @access public
  */
  
  var $GoogleApiBase;

  /**
  * Default Constructor
  *
  * @param array $config options
  * @access public
  */
  
  public function __construct($config) {
    //Select contacts service for login token
    $config['service'] = "cp";
    $this->GoogleApiBase = new GoogleApiBase($config);
    parent::__construct($config);
  }

  public function read($model, $queryData = array()) {
    if (isset($queryData['conditions']['id'])) {
      return $this->GoogleApiBase->sendRequest("http://www.google.com/m8/feeds/contacts/default/full/".$queryData['conditions']['id'], "GET");
    } else {
      $args['max-results'] = ($queryData['limit'] != null)?$queryData['limit']:'25';
      
      if (isset($queryData['order'][0]) && $queryData['order'][0] != NULL) $args['sortorder'] = $queryData['order'][0]; //Sorting order direction. Can be either ascending or descending.
      if (isset($queryData['conditions'])) {
        foreach($queryData['conditions'] AS $key => $value) {
          $args[$key] = $value;
        }
      }
      
      $query = "http://www.google.com/m8/feeds/contacts/default/full" . "?" . http_build_query($args, "", "&");
      $result = $this->GoogleApiBase->sendRequest($query, "GET");
      
      if(isset($queryData['fields']['COUNT']) && $queryData['fields']['COUNT'] == 1){
        $count[0][0] = array('count'=>count($result['feed']['entry']));
        return $count;
      } else {
        return $this->GoogleApiBase->transformContactsObject($result['feed']['entry']);
      }
    }
  }

  public function create($model, $fields = array(), $values = array()) {

  }

  public function update($model, $fields = array(), $values = array()) {

  }

  public function delete($model, $id = null) {

  }
  
  public function calculate(&$model, $func, $params = array()) {
    $params = (array)$params;
    switch (strtolower($func)) {
    case 'count':
      return array('COUNT' => true);
      break;
    case 'max':
      break;
    case 'min':
      break;
    }
  }

  public function query($query, $params, $model) {
    switch ($query) {
    case "findById": // NOT WORKING YET
      $q = "http://www.google.com/m8/feeds/groups/default/full/".$params[0];
      debug($q);
      $result = $this->sendRequest($q, "GET");
      debug($result);
      return $result['feed']['entry'];
      break;
    }
  }

  public function listSources() {
    //return array('google_contacts');
  }

  public function describe($model) {
    //return $this->_schema['google_contacts'];
  }

  // public function insertQueryData($query, $data, $association, $assocData, $model, $linkModel, $stack) {}
  // public function resolveKey( $model, $key ) {}
  // public function rollback( $model ) {}
  // public function sources( $reset = false ) {}
  // public function column( $real ) {}
  // public function commit( $model ) {}
  // public function begin( $model ) {}
  // public function __cacheDescription( $object, $data = NULL ){}
  // public function __destruct(){}
  // public function isInterfaceSupported( $interface ){}
  // public function lastAffected( $source = NULL ){}
  // public function lastInsertId( $source = NULL ){}
  // public function lastNumRows( $source = NULL ){}
  // public function cakeError( $method, $messages = array ( ) ){}
  // public function dispatchMethod( $method, $params = array ( ) ){ }
}
?>
