<?php
    /**
     *
     *
     */
    class AnalyticsHelper extends AppHelper
    {
        public function tracker()
        {
            Configure::write( 'Google.Analytics.profile_id', '10397964-2' );

            $out = '<script type="text/javascript"><!--';
                $out .= 'var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");';
                $out .= 'document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));';
                $out .= '$(document).ready(function(){';
                $out .= 'var pageTracker = _gat._getTracker("UA-'.Configure::read( 'Google.Analytics.profile_id' ).'");';
                $out .= 'pageTracker._trackPageview();';
                $out .= '});';
            $out .= '--></script>';

            return $out;
        }
    }
?>