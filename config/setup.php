<?php
    class GoogleConfig
    {
        /**
         * Current version: http://github.com/dogmatic/cakephp_google_plugin
         * @access public
         * @var string
         */
        public $version = '0.1';

        /**
         * Settings
         * @access public
         * @var array
         */
        public $settings = array();

        /**
         * Singleton Instance
         * @access private
         * @var array
         * @static
         */
        private static $__instance;

        /**
         * @access private
         * @return void
         */
        function __construct()
        {
            /**
            * analytics setup
            */
            $analytics = array(
                'profile_id' => 'xxxxxxxx-x'
            );
            Configure::write( 'Google.Analytics', $analytics  );
        }
    }