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
 * @copyright Copyright (c) 2009 Juan Carlos del Valle ( imekinox )
 * @link http://www.imekinox.com
 * @package google
 * @subpackage google.vendors.GoogleApiContacts
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::import( 'Lib', 'GoogleApiBase' );

/**
 * GoogleApiContacts
 *
 * Datasource for Google Contacts Custom Methods
 */
class GoogleApiContacts extends GoogleApiBase
{
  public function __construct( $config )
  {
    // CP is the key for google contacts api, this is passed to the auth to tell we are using contacts
    $config['service'] = 'cp';
    parent::__construct( $config );
  }

  public function getSchema()
  {
    $schema = array(
                'google_contacts' => array(
                                     'etag' => array(
                                               'type' => 'string',
                                               'null' => true
                                             ),
                                     'contact_id' => array(
                                                     'type' => 'integer',
                                                     'null' => false
                                                   ),
                                     'updated' => array(
                                                  'type' => 'string',
                                                  'null' => true
                                                ),
                                     'edited' => array(
                                                 'type' => 'string',
                                                 'null' => true
                                               ),
                                     'category' => array(
                                                   'type' => 'string',
                                                   'null' => true
                                                 ),
                                     'title' => array(
                                                'type' => 'string',
                                                'null' => false
                                              ),
                                     'name' => array(
                                               'type' => 'string',
                                               'null' => true
                                             ),
                                     'nickname' => array(
                                                   'type' => 'string',
                                                   'null' => true
                                                 ),
                                     'birthday' => array(
                                                   'type' => 'string',
                                                   'null' => true
                                                 ),
                                     'organization' => array(
                                                       'title' => array(
                                                                  'type' => 'string',
                                                                  'null' => true
                                                                ),
                                                       'company' => array(
                                                                    'type' => 'string',
                                                                    'null' => true
                                                                  )
                                                     ),
                                     'email' => array(
                                                'primary' => array(
                                                             'type' => 'boolean',
                                                             'null' => true
                                                           ),
                                                'address' => array(
                                                             'type' => 'string',
                                                             'null' => true
                                                           ),
                                                'category' => array(
                                                              'type' => 'string',
                                                              'null' => true
                                                            )
                                              ),
                                     'im' => array(
                                             'address' => array(
                                                          'type' => 'string',
                                                          'null' => true
                                                        ),
                                             'category' => array(
                                                           'type' => 'string',
                                                           'null' => true
                                                         )
                                           ),
                                     'phones' => array(
                                                 'phone' => array(
                                                            'type' => 'integer',
                                                            'null' => true
                                                          ),
                                                 'category' => array(
                                                               'type' => 'string',
                                                               'null' => true
                                                             )
                                               ),
                                     'address' => array(
                                                  'address' => array(
                                                               'type' => 'string',
                                                               'null' => true
                                                             ),
                                                  'category' => array(
                                                                'type' => 'string',
                                                                'null' => true
                                                              )
                                                ),
                                     'events' => array(
                                                 'date' => array(
                                                           'type' => 'string',
                                                           'null' => true
                                                         ),
                                                 'category' => array(
                                                               'type' => 'string',
                                                               'null' => true
                                                             )
                                               ),
                                     'relations' => array(
                                                    'name' => array(
                                                              'type' => 'string',
                                                              'null' => true
                                                            ),
                                                    'category' => array(
                                                                  'type' => 'string',
                                                                  'null' => true
                                                                )
                                                  ),
                                     'custom' => array(
                                                 'key' => array(
                                                          'type' => 'string',
                                                          'null' => true
                                                        ),
                                                 'value' => array(
                                                            'type' => 'string',
                                                            'null' => true
                                                          )
                                               ),
                                     'websites' => array(
                                                   'address' => array(
                                                                'type' => 'string',
                                                                'null' => true
                                                              ),
                                                   'category' => array(
                                                                 'type' => 'string',
                                                                 'null' => true
                                                               )
                                                 ),
                                     'groups' => array(
                                                 'group_id' => array(
                                                               'type' => 'string',
                                                               'null' => true
                                                             ),
                                                 'deleted' => array(
                                                              'type' => 'boolean',
                                                              'null' => true
                                                            )
                                               )
                                   )
              );
    return $schema;
  }
}
