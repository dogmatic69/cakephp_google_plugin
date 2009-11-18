<?php
    /**
     *
     *
     * @version $Id$
     * @copyright 2009
     */
    class ChartHelper extends AppHelper
    {
        var $helpers = array( 'Html', 'Session', 'Number' );

        var $country_to_continent = array(
            'Africa' => array(
                'KM', 'GW', 'KE', 'NA', 'LS', 'LR', 'LY', 'MG', 'MW', 'ML', 'GN', 'GH',
                'CG', 'CI', 'DJ', 'EG', 'GQ', 'ER', 'ET', 'GA', 'GM', 'MR', 'MU', 'YT',
                'SO', 'ZA', 'SD', 'SZ', 'TZ', 'TG', 'TN', 'UG', 'EH', 'SL', 'SC', 'MA',
                'MZ', 'NE', 'NG', 'RE', 'RW', 'SH', 'ST', 'SN', 'ZM', 'BF', 'BI', 'BJ',
                'AO', 'CM', 'CV', 'ZW', 'DZ', 'BW', 'TD', 'CF'
            ),
            'Antarctica' => array(
                'GS', 'HM', 'AQ', 'TF', 'BV'
            ),
            'Asia' => array(
               'IO', 'AM', 'ID', 'SG', 'KP', 'MM', 'JO', 'JP', 'IN', 'IL', 'BD', 'PK',
               'PH', 'NP', 'BN', 'MN', 'LB', 'HK', 'GE', 'TL', 'KR', 'CY', 'UZ', 'VN',
               'MO', 'TH', 'MY', 'LA', 'KH', 'CC', 'TW', 'KG', 'LK', 'CX', 'MV', 'CN',
               'BT'
            ),
            'Europe' => array(
                'MK', 'MC', 'AL', 'BE', 'MT', 'MD', 'ME', 'BY', 'ES', 'SJ', 'SE', 'CH',
                'AD', 'FO', 'TR', 'UA', 'GB', 'SI', 'SK', 'NL', 'NO', 'PL', 'PT', 'RO',
                'RU', 'AT', 'SM', 'RS', 'AX', 'LU', 'LT', 'FI', 'IT', 'IM', 'IE', 'FR',
                'HR', 'GR', 'IS', 'VA', 'HU', 'GI', 'DE', 'BG', 'LV', 'JE', 'BA', 'EE',
                'GG', 'LI', 'DK', 'CZ'
            ),
            'ME' => array(
                'PS', 'OM', 'QA', 'AZ', 'AE', 'TM', 'SA', 'AF', 'KZ', 'SY', 'TJ', 'YE',
                'IQ', 'IR', 'KW', 'BH'
            ),
            'North America' => array(
                'GD', 'GP', 'KN', 'DM', 'PM', 'VC', 'AG', 'DO', 'AW', 'AI', 'SV', 'GL',
                'CA', 'TT', 'KY', 'BS', 'US', 'HN', 'AN', 'BB', 'MS', 'JM', 'MX', 'BZ',
                'MQ', 'BM', 'NI', 'CR', 'TC', 'LC', 'VG', 'PA', 'GT', 'VI', 'CU', 'HT',
                'PR'
            ),
            'Oceania' => array(
                'WF', 'TK', 'VU', 'TV', 'CK', 'AS', 'UM', 'TO', 'NR', 'PN', 'PG', 'PW',
                'FM', 'MP', 'NF', 'NU', 'NZ', 'NC', 'GU', 'SB', 'FJ', 'KI', 'MH', 'PF',
                'AU', 'WS'
            ),
            'South America' => array(
                'CL', 'CO', 'VE', 'BO', 'FK', 'UY', 'GY', 'PE', 'AR', 'BR', 'PY', 'GF',
                'SR', 'EC'
            )
        );

        var $settings = array(
            'api_address' => 'http://chart.apis.google.com/chart?',
            'size' => array(
                'width'  => 300,
                'height' => 300,
                'name'   => 'chs='
            ),
            'charts' => array(
                'meter' => array(
                    'name'  => 'cht=gom',
                    'data'  => 'chd=t:',
                    'label' => 'chl='
                ),
                'sparkline' => array(
                    'name'   => 'cht=ls',
                    'color'  => 'chco=',
                    'data'   => 'chd=t:',
                    'labels' => array(
                        'axis'  => array(
                            'name'  => 'chxt=',
                            'where' => array( 'x', 'y' )
                        ),
                        'label' => array(
                            'name' => 'chxl=',
                            'data' => array()
                        ),
                    ),
                ),
                'pie' => array(
                    'name'  => 'cht=p3',
                    'data'  => 'chd=t:',
                    'label' => 'chl='
                ),
                'map' => array(
                    'type'  => array(
                        'name' => 'chtm=',
                        'type' => 'world'
                    ),
                    'colors' => array(
                        'name' => 'chco=',
                        'seperator' => ','
                    ),
                    'places' => array(
                        'name' => 'chld=',
                        'seperator' => ''
                    ),
                    'data' => array(
                        'name' => 'chd=t:',
                        'seperator' => ','
                    ),
                    'size' => array(
                        'name' => 'chs='
                    )
                )
            )
        );

        function map(  $type = 'world', $size = 'large', $data = null )
        {
            $chart = $this->settings['charts']['map'];

            if ( !$data )
            {
                return false;
            }
            if ( !$type )
            {
                $type = $chart['type']['type'];
            }

            switch( $size )
            {
                case 'small':
                    $width  = 220;
                    $height = 110;
                    break;

                case'medium' :
                    $width  = 330;
                    $height = 165;
                    break;

                case'large' :
                    $width  = 440;
                    $height = 220;
                    break;
            } // switch
            $size = $width.'x'.$height;

            $render = $this->settings['api_address'].'cht=t&'.
                $this->settings['size']['name'].$size.'&amp;'.
                $chart['data']['name'].implode( $chart['data']['seperator'], $data['amount'] ).'&amp;'.
                $chart['colors']['name'].implode( $chart['colors']['seperator'], $data['colors'] ).'&amp;'.
                $chart['places']['name'].implode( $chart['places']['seperator'], $data['countries'] ).'&amp;'.
                $chart['type']['name'].$type.'&amp;'.
                'chf=bg,s,EAF7FE';



            return $this->Html->image(
                $render,
                array(
                    'title'  => $chart['type']['type'],
                    'alt'    => $chart['type']['type']
                )
            );

        }

        function pie3D( $data = array(), $labels = array(), $size = array() )
        {
            $chart = $this->settings['charts']['pie'];

            if ( empty( $data ) )
            {
                return false;
            }

            if ( empty( $size ) )
            {
                $size = $this->settings['size'];
            }

            if ( $check = $this->checkSize( $size ) != true )
            {
                return $check;
            }

            $render = $this->settings['api_address'].
                $this->settings['size']['name'].
                    $size['width'].'x'.$size['height'].'&amp;'.
                $chart['name'].'&amp;'.
                $chart['data'].implode( ',', $data ).'&amp;'.
                $chart['label'].implode( '|', $labels );

            return $this->Html->image(
                $render,
                array(
                    'title'  => implode( ' vs. ', $labels ),
                    'alt'    => implode( ' vs. ', $labels ),
                    'width'  => $size['width'],
                    'height' => $size['height']
                )
            );
        }

        function sparkline( $data = array(), $axis_label = array(), $size = array() )
        {
            $chart = $this->settings['charts']['sparkline'];

            if ( empty( $data ) )
            {
                return false;
            }

            if ( empty( $size ) )
            {
                $size = $this->settings['size'];
            }

            if ( $check = $this->checkSize( $size ) != true )
            {
                return $check;
            }

            $max = 0;
            foreach( $data as $k => $v )
            {
                $data[$k] = (int)$v + 0;
                $bottom_label[] = $k;

                if ( $v > $max )
                {
                    $max = $v;
                }
            }
            $i = 0;
            while( $i <= $max )
            {
                $x_inc[$i] = $i;
                $i++;
            } // while

            $render = $this->settings['api_address'].
                $this->settings['size']['name'].
                    $size['width'].'x'.$size['height'].'&amp;'.
                $chart['name'].'&amp;'.
                $chart['data'].implode( ',', $data ).'&amp;'.
                $chart['labels']['axis']['name'].implode( ',', $chart['labels']['axis']['where'] ).'&amp;'.
                $chart['labels']['label']['name'].
                    '0:|'.implode( '|', $bottom_label ).'|'.
                    '1:|'.implode( '|', $x_inc ).
                    '&amp;chds=0,'.$max;

            return $this->Html->image(
                $render,
                array(
                    'title'  => '',
                    'alt'    => '',
                    'width'  => $size['width'],
                    'height' => $size['height']
                )
            );
        }


        function meter( $data = null, $label = '', $size = array() )
        {
            $chart = $this->settings['charts']['meter'];

            if ( $data == null || is_array( $data ) )
            {
                return false;
            }

            if ( empty( $size ) )
            {
                $size = $this->settings['size'];
            }

            $size['height'] = $size['width'] / 2;

            if ( $check = $this->checkSize( $size ) != true )
            {
                return $check;
            }

            if ( $data <= 0 )
            {
                $data = 1;
            }

            elseif ( $data >= 100 )
            {
                $data = 100;
            }

            $render = $this->settings['api_address'].
                $this->settings['size']['name'].
                    $size['width'].'x'.$size['height'].'&amp;'.
                $chart['name'].'&amp;'.
                $chart['data'].$this->Number->precision( $data, 0 ).'&amp;'.
                $chart['label'].$label;

            return $this->Html->image(
                $render,
                array(
                    'title'  => __( 'Health: ', true ).$this->Number->toPercentage( $data, 0 ),
                    'alt'    => __( 'Health: ', true ).$this->Number->toPercentage( $data, 0 ),
                    'width'  => $size['width'],
                    'height' => $size['height']
                )
            );
        }

        function checkSize( $size = array() )
        {
            if ( empty( $size ) )
            {
                return false;
            }

            $total = $size['width'] * $size['height'];

            if ( $total >= 300000 )
            {
                return false;
            }

            return true;
        }
    }
?>