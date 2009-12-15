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
* @copyright     Copyright (c) 2009 Juan Carlos del Valle ( imekinox )
* @link http://www.imekinox.com
* @package       google
* @subpackage    google.vendors.GoogleApiBase
* @license MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

App::import('Core', 'HttpSocket');
App::import('Core', 'Session');

/**
* GoogleSource
*
* Datasource for Google API
*/
class GoogleApiBase {

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
    $cookie_key = $session->read('GoogleClientLogin'.$_toPost['service'].'._auth_key');
    if ($cookie_key == NULL || $cookie_key == "") {
      //Geting auth key via HttpSocket
      $HttpSocket = new HttpSocket();
      $results = $HttpSocket->post($this->_login_uri, $_toPost);
      $first_split = split("\n",$results);
      foreach($first_split as $string) {
        $arr = split("=",$string);
        if ($arr[0] == "Auth") $this->_auth_key = $arr[1];
      }
      $session->write('GoogleClientLogin'.$_toPost['service'].'._auth_key', $this->_auth_key);
    } else {
      $this->_auth_key = $cookie_key;
    }
  }

  /**
  * Send Request
  *
  * @param string $url URL to do the request
  * @param string $method GET or POST
  * @return xml object
  * @access public
  */
  public function sendRequest($url, $method) {
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


  /**
  * Transform Google Contacts object into a smaller/cleaner object
  *
  * @param object $object Google Contacts result object
  * @return object
  * @access public
  */

  public function transformContactsObject($object) {
    foreach($object as $i => $value) {
      $new_object[$i]['etag'] = $object[$i]['gd$etag'];
      $tmp = split("/",$object[$i]['id']['$t']);
      $new_object[$i]['contact_id'] = $tmp[8];
      $new_object[$i]['updated'] = $object[$i]['updated']['$t'];
      $new_object[$i]['edited'] = $object[$i]['app$edited']['$t'];
      $tmp = split("#",$object[$i]['category'][0]['term']);
      $new_object[$i]['category'] = $tmp[1];
      $new_object[$i]['title'] = $object[$i]['title']['$t'];
      if (isset($object[$i]['content'])) {
        $new_object[$i]['content'] = $object[$i]['content']['$t'];
      }
      if (isset($object[$i]['gd$name'])) {
        $new_object[$i]['name'] = $object[$i]['gd$name']['gd$fullName']['$t'];
      }
      if (isset($object[$i]['gContact$nickname'])) {
        $new_object[$i]['nickname'] = $object[$i]['gContact$nickname']['$t'];
      }
      if (isset($object[$i]['gContact$birthday'])) {
        $new_object[$i]['birthday'] = $object[$i]['gContact$birthday']['when'];
      }
      if (isset($object[$i]['gd$organization'])) {
        $new_object[$i]['organization']['title'] = $object[$i]['gd$organization'][0]['gd$orgTitle']['$t'];
        $new_object[$i]['organization']['company'] = $object[$i]['gd$organization'][0]['gd$orgName']['$t'];
      }
      if (isset($object[$i]['gd$email'])) {
        foreach($object[$i]['gd$email'] AS $key => $value) {
          $var_name = split("#",$object[$i]['gd$email'][$key]['rel']);
          $new_object[$i]['email'][$key]['primary'] = isset($object[$i]['gd$email'][$key]['primary']);
          $new_object[$i]['email'][$key]['address'] = $object[$i]['gd$email'][$key]['address'];
          $new_object[$i]['email'][$key]['category'] = $var_name[1];
        }
      }
      if (isset($object[$i]['gd$im'])) {
        foreach($object[$i]['gd$im'] AS $key => $value) {
          $var_name = split("#",$object[$i]['gd$im'][$key]['protocol']);
          $new_object[$i]['im'][$key]['address'] = $object[$i]['gd$im'][$key]['address'];
          $new_object[$i]['im'][$key]['category'] = $var_name[1];
        }
      }
      if (isset($object[$i]['gd$phoneNumber'])) {
        foreach($object[$i]['gd$phoneNumber'] AS $key => $value) {
          $var_name = split("#",$object[$i]['gd$phoneNumber'][$key]['rel']);
          $new_object[$i]['phones'][$key]['phone'] = $object[$i]['gd$phoneNumber'][$key]['$t'];
          $new_object[$i]['phones'][$key]['category'] = $var_name[1];
        }
      }
      if (isset($object[$i]['gd$structuredPostalAddress'])) {
        foreach($object[$i]['gd$structuredPostalAddress'] AS $key => $value) {
          $var_name = split("#",$object[$i]['gd$structuredPostalAddress'][$key]['rel']);
          $new_object[$i]['address'][$key]['address'] = $object[$i]['gd$structuredPostalAddress'][$key]['gd$formattedAddress']['$t'];
          $new_object[$i]['address'][$key]['category'] = $var_name[1];
        }
      }
      if (isset($object[$i]['gContact$event'])) {
        foreach($object[$i]['gContact$event'] AS $key => $value) {
          $new_object[$i]['events'][$key]['date'] = $object[$i]['gContact$event'][$key]['gd$when']['startTime'];
          $new_object[$i]['events'][$key]['category'] = $object[$i]['gContact$event'][$key]['rel'];
        }
      }
      if (isset($object[$i]['gContact$relation'])) {
        foreach($object[$i]['gContact$relation'] AS $key => $value) {
          $new_object[$i]['relations'][$key]['name'] = $object[$i]['gContact$relation'][$key]['$t'];
          $new_object[$i]['relations'][$key]['category'] = $object[$i]['gContact$relation'][$key]['rel'];
        }
      }
      if (isset($object[$i]['gContact$userDefinedField'])) {
        $new_object[$i]['custom'] = $object[$i]['gContact$userDefinedField'];
      }
      if (isset($object[$i]['gContact$website'])) {
        foreach($object[$i]['gContact$website'] AS $key => $value) {
          $new_object[$i]['websites'][$key]['address'] = $object[$i]['gContact$website'][$key]['href'];
          $new_object[$i]['websites'][$key]['category'] = $object[$i]['gContact$website'][$key]['rel'];
        }
      }
      if (isset($object[$i]['gContact$groupMembershipInfo'])) {
        foreach($object[$i]['gContact$groupMembershipInfo'] AS $key => $value) {
          $var_name = split("/",$object[$i]['gContact$groupMembershipInfo'][$key]['href']);
          $new_object[$i]['groups'][$key]['group_id'] = $var_name[8];
          $new_object[$i]['groups'][$key]['deleted'] = $object[$i]['gContact$groupMembershipInfo'][$key]['deleted'];
        }
      }
    }
    return $new_object;
  }
}
?>
