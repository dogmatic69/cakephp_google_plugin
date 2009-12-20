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
     * @copyright Copyright (c) 2009 Juan Carlos del Valle ( imekinox )
     * @link http://www.imekinox.com
     * @package google
     * @subpackage google.models.datasources.google_contacts
     * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
     */

    App::import( 'Lib', 'GoogleApiContacts' );

    /**
     * GoogleContactsSource
     *
     * Datasource for Google Contacts
     */

    class GoogleContactsSource extends DataSource
    {
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
         * Google Contacts custom schema
         *
         * @var Array
         * @access protected
         */
        protected $_schema;

        /**
         * Limit unless specified.
         *
         * @var int
         * @access protected
         */
        protected $_limit = 25;

        /**
         * Default Constructor
         *
         * @param array $config options
         * @access public
         */

        public function __construct( $config )
        {
            // Select contacts service for login token
            $this->GoogleApiContacts = new GoogleApiContacts( $config );
            $this->_schema = $this->GoogleApiContacts->getSchema();
            parent::__construct( $config );
        }

        public function read( $model, $queryData = array() )
        {
            if ( isset( $queryData['conditions']['id'] ) )
            {
                return $this->GoogleApiContacts->sendRequest( 'http://www.google.com/m8/feeds/contacts/default/full/'.$queryData['conditions']['id'], 'GET' );
            }
            else
            {
                $args['max-results'] = ( $queryData['limit'] != null ) ? $queryData['limit'] : $this->_limit;

                //Sorting order direction. Can be either ascending or descending.
                if ( isset( $queryData['order'][0] ) && $queryData['order'][0] != NULL )
                {
                    /**
                    * @todo add a check to make sure the order is ascending || descending
                    * maybe throw error or set default.
                    */
                    $args['sortorder'] = $queryData['order'][0];
                }

                if ( isset( $queryData['conditions'] ) )
                {
                    foreach( $queryData['conditions'] AS $key => $value )
                    {
                        $args[$key] = $value;
                    }
                }

                /**
                * @todo why the .'?'. could just be part of the first string.
                */
                $query = 'http://www.google.com/m8/feeds/contacts/default/full'.'?'.http_build_query( $args, '', '&' );
                $result = $this->GoogleApiContacts->sendRequest( $query, 'GET' );

                if ( isset( $queryData['fields']['COUNT'] ) && (int)$queryData['fields']['COUNT'] == 1 )
                {
                    $count[0][0] = array( 'count' => count( $result['feed']['entry'] ) );
                    return $count;
                }
                else
                {
                    return $this->GoogleApiContacts->transformObject( $result['feed']['entry'] );
                }
            }
        }

        public function create( $model, $fields = array(), $values = array() )
        {
            debug( 'create' );
        }

        public function update( $model, $fields = array(), $values = array() )
        {
            debug( $fields );
            debug( $values );
        }

        public function delete( $model, $id = null )
        {
            debug( 'delete' );
        }

        public function calculate( &$model, $func, $params = array() )
        {
            $params = (array)$params;
            switch ( strtolower( $func ) )
            {
                case 'count':
                    return array( 'COUNT' => true );
                    break;

                case 'max':
                    break;

                case 'min':
                    break;
            }
        }

        public function query( $query, $params, $model )
        {
            debug( $query );
            switch ( $query )
            {
                /**
                * @todo not working.
                */
                case 'findById':
                    $q = 'http://www.google.com/m8/feeds/groups/default/full/'.$params[0];
                    debug( $q );
                    $result = $this->sendRequest( $q, 'GET' );
                    debug( $result );
                    return $result['feed']['entry'];
                    break;
            }
        }

        public function listSources()
        {
            return array( 'google_contacts' );
        }

        public function describe( $model )
        {
            return $this->_schema['google_contacts'];
        }

        /**
        * @todo public function insertQueryData($query, $data, $association, $assocData, $model, $linkModel, $stack) { debug("iq"); }
        * @todo public function resolveKey( $model, $key ) { debug("key"); }
        * @todo public function rollback( $model ) { debug("rollback"); }
        * @todo public function sources( $reset = false ) {}
        * @todo public function column( $real ) {}
        * @todo public function commit( $model ) { debug("commit"); }
        * @todo public function begin( $model ) { debug("begin"); }
        * @todo public function __cacheDescription( $object, $data = NULL ){}
        * @todo public function __destruct(){}
        * @todo public function isInterfaceSupported( $interface ){ debug("interface"); }
        * @todo public function lastAffected( $source = NULL ){}
        * @todo public function lastInsertId( $source = NULL ){}
        * @todo public function lastNumRows( $source = NULL ){}
        * @todo public function cakeError( $method, $messages = array ( ) ){ debug("Error"); }
        * @todo public function dispatchMethod( $method, $params = array ( ) ){ debug("method" . $method); }
        */
    }
?>