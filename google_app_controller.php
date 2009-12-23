<?php
    App::import(
        array(
            'type' => 'File',
            'name' => 'Google.GoogleConfig',
            'file' => 'config'. DS .'setup.php'
        )
    );

    /**
     *
     *
     */
    class GoogleAppController extends AppController
    {
        function beforeFilter()
        {
            parent::beforeFilter();

            $GoogleConfig = new GoogleConfig();
        }
    }
?>