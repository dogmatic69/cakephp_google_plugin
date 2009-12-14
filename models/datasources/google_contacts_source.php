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
* @subpackage    google.models.datasources.google_contacts
* @license MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

App::import('Core', 'HttpSocket');
App::import('Core','Session');

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
  * Client Login URL
  *
  * @var string
  * @access private
  */

  private $_uri = "https://www.google.com/accounts/ClientLogin"; //you'll have to uncomment extension=php_openssl.dll from php.ini

  /**
  * Auth key returned by google API
  *
  * @var string
  * @access private
  */

  private $_auth_key;

  /**
  * Method used to make requests (curl or file_get_contents)
  *
  * @var string
  * @access private
  */

  private $_method;

  /**
  * Default Constructor
  *
  * @param array $config options
  * @access public
  */

  public function __construct($config) {
    $_toPost['accountType'] = $config['accountType'];
    $_toPost['Email'] = $config['Email'];
    $_toPost['Passwd'] = $config['Passwd'];
    $_toPost['service'] = $config['service'];
    $_toPost['source'] = $config['source'];

    // Initializing Cake Session
    $session = new CakeSession();
    $session->start();
    
    // Validating if curl is available
    if (function_exists('curl_init')) {
      $this->_method = 'curl';
    } else {
      $this->_method = 'fopen';
    }
    
    //Looking for auth key in cookie of google api client login
    $cookie_key = $session->read('GoogleClientLogin._auth_key');
    if($cookie_key == NULL || $cookie_key == ""){
      $HttpSocket = new HttpSocket();
      $results = $HttpSocket->post($this->_uri, $_toPost);
      $first_split = split("\n",$results);
      foreach($first_split as $string) {
        $arr = split("=",$string);
        if ($arr[0] == "Auth") $this->_auth_key = $arr[1];
      }
      $session->write('GoogleClientLogin._auth_key', $this->_auth_key);
    } else {
      $this->_auth_key = $cookie_key;
    }
    parent::__construct($config);
  }

  /**
  * Send Request
  *
  * @param string $url URL to do the request
  * @param string $method GET or POST
  * @return xml object
  * @access private
  */
  private function sendRequest($url, $method) {
    /*
      Could'nt find a way to do it via $HttpSocket i got empty result

      $auth['header'] = "Authorization: GoogleLogin auth=" . $this->_auth_key;
      $result = $HttpSocket->get("http://www.google.com/m8/feeds/contacts/jc.ekinox@gmail.com/full", array(), $auth);
    */
    $header[] = "Authorization: GoogleLogin auth=" . $this->_auth_key;
    $header[] = "GData-Version: 3.0";
    if ($this->_method == 'curl') {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
      $json = curl_exec($ch);
      curl_close($ch);
    } else {
      $opts = array(
                'http'=>array(
                         'method'=>$method,
                         'header'=>$header[0]
                       )
              );
      $context = stream_context_create($opts);
      $json = file_get_contents($url, false, $context);
    }
    $result = json_decode($json,true);
    return $result;
  }

  public function read($model, $queryData = array()) {
    if (isset($queryData['conditions']['id'])) {
      return $this->sendRequest("http://www.google.com/m8/feeds/contacts/default/full".$queryData['conditions']['id'], "GET");
    } else {
      $args['alt'] = 'json';
      $args['max-results'] = ($queryData['limit'] != null)?$queryData['limit']:'25';
      if (isset($queryData['order'][0]) && $queryData['order'][0] != NULL) $args['sortorder'] = $queryData['order'][0]; //Sorting order direction. Can be either ascending or descending.
      if (isset($queryData['conditions'])) {
        foreach($queryData['conditions'] AS $key => $value) {
          $args[$key] = $value;
        }
      }
      $query = "http://www.google.com/m8/feeds/contacts/default/full" . "?" . http_build_query($args, "", "&");
      $result = $this->sendRequest($query, "GET");
      $count[0][0] = array('count'=>count($result['feed']['entry']));
      return (isset($queryData['fields']['COUNT']) && $queryData['fields']['COUNT'] == 1) ? $count : $result['feed']['entry'];
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
