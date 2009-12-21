<?php
/**
* Google Api Contacts Class
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
* @subpackage    google.vendors.GoogleApiContacts
* @license MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

App::import('Lib', 'GoogleApiBase');

/**
* GoogleApiContacts
*
* Datasource for Google Contacts Custom Methods
*/
class GoogleApiContacts extends GoogleApiBase {

  public function __construct($config) {
    $config['service'] = "cp";
    parent::__construct($config);
  }

  /**
  * Get Google contacts custom schema
  *
  * @return array
  * @access public
  */

  public function getSchema() {
    return array(
             'google_contacts' => array(
                                  'gd:etag' => array(
                                               'type' => 'string',
                                               'null' => true
                                             ),
                                  'id'=>array(
                                         'type' => 'string',
                                         'null' => false
                                       ),
                                  'updated'=>array(
                                              'type' => 'string',
                                              'null' => true
                                            ),
                                  'edited'=>array(
                                             'type' => 'string',
                                             'null' => true
                                           ),
                                  'Category'=>array(
                                               'scheme' => array(
                                                           'type' => 'string',
                                                           'null' => true
                                                         ),
                                               'term' => array(
                                                         'type' => 'string',
                                                         'null' => true
                                                       )
                                             ),
                                  'title'=>array(
                                            'type' => 'string',
                                            'null' => false
                                          ),
                                  'content'=>array(
                                              'type' => 'string',
                                              'null' => true
                                            ),
                                  'Name'=>array(
                                           'fullName' => array(
                                                         'type' => 'string',
                                                         'null' => true
                                                       )
                                         ),
                                  'nickname'=>array(
                                               'type' => 'string',
                                               'null' => true
                                             ),
                                  'Birthday'=>array(
                                               'when' => array(
                                                         'type' => 'string',
                                                         'null' => true
                                                       )
                                             ),
                                  'Organization'=>array(
                                                   'rel'=>array(
                                                           'type' => 'string',
                                                           'null' => true
                                                         ),
                                                   'orgName'=>array(
                                                               'type' => 'string',
                                                               'null' => true
                                                             ),
                                                   'orgTitle'=>array(
                                                                'type' => 'string',
                                                                'null' => true
                                                              )
                                                 ),
                                  'StructuredPostalAddress'=>array(
                                                              'rel'=>array(
                                                                      'type' => 'string',
                                                                      'null' => true
                                                                    ),
                                                              'formattedAdress'=>array(
                                                                                  'type' => 'string',
                                                                                  'null' => true
                                                                                )
                                                            ),
                                  'Event'=>array(
                                            'rel'=>array(
                                                    'type' => 'string',
                                                    'null' => true
                                                  ),
                                            'When'=>array(
                                                     'startTime'=>array(
                                                                   'type' => 'string',
                                                                   'null' => true
                                                                 )
                                                   )
                                          ),
                                  'UserDefinedField'=>array(
                                                       'key'=>array(
                                                               'type' => 'string',
                                                               'null' => true
                                                             ),
                                                       'value'=>array(
                                                                 'type' => 'string',
                                                                 'null' => true
                                                               )
                                                     ),
                                  'Link'=>array(
                                           'rel'=>array(
                                                   'type' => 'string',
                                                   'null' => true
                                                 ),
                                           'type'=>array(
                                                    'type' => 'string',
                                                    'null' => true
                                                  ),
                                           'href'=>array(
                                                    'type' => 'string',
                                                    'null' => true
                                                  )
                                         ),
                                  'Email'=>array(
                                            'rel'=>array(
                                                    'type' => 'string',
                                                    'null' => true
                                                  ),
                                            'address'=>array(
                                                        'type' => 'string',
                                                        'null' => true
                                                      ),
                                            'primary'=>array(
                                                        'type' => 'boolean',
                                                        'null' => true
                                                      )
                                          ),
                                  'Im'=>array(
                                         'address'=>array(
                                                     'type' => 'string',
                                                     'null' => true
                                                   ),
                                         'protocol'=>array(
                                                      'type' => 'string',
                                                      'null' => true
                                                    ),
                                         'rel'=>array(
                                                 'type' => 'string',
                                                 'null' => true
                                               )
                                       ),
                                  'PhoneNumber'=>array(
                                                  'value'=>array(
                                                            'type' => 'integer',
                                                            'null' => true
                                                          ),
                                                  'rel'=>array(
                                                          'type' => 'string',
                                                          'null' => true
                                                        )
                                                ),
                                  'Relation'=>array(
                                               'value'=>array(
                                                         'type' => 'string',
                                                         'null' => true
                                                       ),
                                               'rel'=>array(
                                                       'type' => 'string',
                                                       'null' => true
                                                     )
                                             ),
                                  'Website'=>array(
                                              'href'=>array(
                                                       'type' => 'string',
                                                       'null' => true
                                                     ),
                                              'rel'=>array(
                                                      'type' => 'string',
                                                      'null' => true
                                                    )
                                            ),
                                  'GroupMembershipInfo'=>array(
                                                          'deleted'=>array(
                                                                      'type' => 'boolean',
                                                                      'null' => true
                                                                    ),
                                                          'href'=>array(
                                                                   'type' => 'string',
                                                                   'null' => true
                                                                 )
                                                        )
                                )
           );
  }
}
?>
