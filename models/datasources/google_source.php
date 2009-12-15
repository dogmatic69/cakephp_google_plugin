<?php
/**
* Google Base Datasource
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
* @subpackage    google.models.datasources.google
* @license MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

App::import('Core', 'HttpSocket');
App::import('Core','Session');

/**
* GoogleSource
*
* Datasource for Google API
*/
class GoogleSource extends DataSource {

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
  var $description = 'Google Base Datasource';

  /**
  * Client Login URL
  *
  * @var string
  * @access private
  */

  private $_login_uri = "https://www.google.com/accounts/ClientLogin"; //you'll have to uncomment extension=php_openssl.dll from php.ini

  /**
  * Auth key returned by google API
  *
  * @var string
  * @access private
  */

  protected $_auth_key;

  /**
  * Method used to make requests (curl or file_get_contents)
  *
  * @var string
  * @access private
  */

  protected $_method;

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
      //Geting auth key via HttpSocket
      $HttpSocket = new HttpSocket();
      $results = $HttpSocket->post($this->_login_uri, $_toPost);
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
  protected function sendRequest($url, $method) {
    /*
      Could'nt find a way to do it via HttpSocket i got empty result

      $auth['header'] = "Authorization: GoogleLogin auth=" . $this->_auth_key;
      $result = $HttpSocket->get("http://www.google.com/m8/feeds/contacts/jc.ekinox@gmail.com/full", array(), $auth);
    */
    $url = $url . "&alt=json";
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
}
?>
