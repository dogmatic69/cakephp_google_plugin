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
            /**
            * @todo why is this just cp. could it not be more descriptive?
            */
            $config['service'] = 'cp';
            parent::__construct( $config );
        }

        /**
         * Transform Google Contacts object into a smaller/cleaner object
         *
         * @param object $object Google Contacts result object
         * @return object
         * @access public
         */

        public function transformObject( $object )
        {
            foreach( $object as $i => $value )
            {
                $new_object[$i]['etag']       = $object[$i]['gd$etag'];
                $tmp                          = split( '/', $object[$i]['id']['$t'] );
                $new_object[$i]['contact_id'] = $tmp[8];
                $new_object[$i]['updated']    = $object[$i]['updated']['$t'];
                $new_object[$i]['edited']     = $object[$i]['app$edited']['$t'];
                $tmp                          = split( '#', $object[$i]['category'][0]['term'] );
                $new_object[$i]['category']   = $tmp[1];
                $new_object[$i]['title']      = $object[$i]['title']['$t'];

                if ( isset( $object[$i]['content'] ) )
                {
                    $new_object[$i]['content'] = $object[$i]['content']['$t'];
                }

                if ( isset( $object[$i]['gd$name'] ) )
                {
                    $new_object[$i]['name'] = $object[$i]['gd$name']['gd$fullName']['$t'];
                }

                if ( isset( $object[$i]['gContact$nickname'] ) )
                {
                    $new_object[$i]['nickname'] = $object[$i]['gContact$nickname']['$t'];
                }

                if ( isset( $object[$i]['gContact$birthday'] ) )
                {
                    $new_object[$i]['birthday'] = $object[$i]['gContact$birthday']['when'];
                }

                if ( isset( $object[$i]['gd$organization'] ) )
                {
                    $new_object[$i]['organization']['title']   = $object[$i]['gd$organization'][0]['gd$orgTitle']['$t'];
                    $new_object[$i]['organization']['company'] = $object[$i]['gd$organization'][0]['gd$orgName']['$t'];
                }

                if ( isset( $object[$i]['gd$email'] ) )
                {
                    foreach( $object[$i]['gd$email'] as $key => $value )
                    {
                        $var_name                                  = split( '#', $object[$i]['gd$email'][$key]['rel'] );
                        $new_object[$i]['email'][$key]['primary']  = isset( $object[$i]['gd$email'][$key]['primary'] );
                        $new_object[$i]['email'][$key]['address']  = $object[$i]['gd$email'][$key]['address'];
                        $new_object[$i]['email'][$key]['category'] = $var_name[1];
                    }
                }

                if ( isset( $object[$i]['gd$im'] ) )
                {
                    foreach( $object[$i]['gd$im'] as $key => $value )
                    {
                        $var_name                               = split( '#', $object[$i]['gd$im'][$key]['protocol'] );
                        $new_object[$i]['im'][$key]['address']  = $object[$i]['gd$im'][$key]['address'];
                        $new_object[$i]['im'][$key]['category'] = $var_name[1];
                    }
                }

                if ( isset( $object[$i]['gd$phoneNumber'] ) )
                {
                    foreach( $object[$i]['gd$phoneNumber'] as $key => $value )
                    {
                        $var_name                                   = split( '#', $object[$i]['gd$phoneNumber'][$key]['rel'] );
                        $new_object[$i]['phones'][$key]['phone']    = $object[$i]['gd$phoneNumber'][$key]['$t'];
                        $new_object[$i]['phones'][$key]['category'] = $var_name[1];
                    }
                }

                if ( isset( $object[$i]['gd$structuredPostalAddress'] ) )
                {
                    foreach( $object[$i]['gd$structuredPostalAddress'] as $key => $value )
                    {
                        $var_name                                    = split( '#', $object[$i]['gd$structuredPostalAddress'][$key]['rel'] );
                        $new_object[$i]['address'][$key]['address']  = $object[$i]['gd$structuredPostalAddress'][$key]['gd$formattedAddress']['$t'];
                        $new_object[$i]['address'][$key]['category'] = $var_name[1];
                    }
                }

                if ( isset( $object[$i]['gContact$event'] ) )
                {
                    foreach( $object[$i]['gContact$event'] as $key => $value )
                    {
                        $new_object[$i]['events'][$key]['date']     = $object[$i]['gContact$event'][$key]['gd$when']['startTime'];
                        $new_object[$i]['events'][$key]['category'] = $object[$i]['gContact$event'][$key]['rel'];
                    }
                }

                if ( isset( $object[$i]['gContact$relation'] ) )
                {
                    foreach( $object[$i]['gContact$relation'] as $key => $value )
                    {
                        $new_object[$i]['relations'][$key]['name']     = $object[$i]['gContact$relation'][$key]['$t'];
                        $new_object[$i]['relations'][$key]['category'] = $object[$i]['gContact$relation'][$key]['rel'];
                    }
                }

                if ( isset( $object[$i]['gContact$userDefinedField'] ) )
                {
                    $new_object[$i]['custom'] = $object[$i]['gContact$userDefinedField'];
                }

                if ( isset( $object[$i]['gContact$website'] ) )
                {
                    foreach( $object[$i]['gContact$website'] as $key => $value )
                    {
                        $new_object[$i]['websites'][$key]['address']  = $object[$i]['gContact$website'][$key]['href'];
                        $new_object[$i]['websites'][$key]['category'] = $object[$i]['gContact$website'][$key]['rel'];
                    }
                }

                if ( isset( $object[$i]['gContact$groupMembershipInfo'] ) )
                {
                    foreach( $object[$i]['gContact$groupMembershipInfo'] as $key => $value )
                    {
                        $var_name                                   = split( '/', $object[$i]['gContact$groupMembershipInfo'][$key]['href'] );
                        $new_object[$i]['groups'][$key]['group_id'] = $var_name[8];
                        $new_object[$i]['groups'][$key]['deleted']  = $object[$i]['gContact$groupMembershipInfo'][$key]['deleted'];
                    }
                }
            }

            return $new_object;
        }

        /**
         * Get Google contacts custom schema
         *
         * @return array
         * @access public
         */

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

?>