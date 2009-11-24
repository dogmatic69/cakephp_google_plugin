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

        var $setup = array(
            'pie3d' => array(
                //required
                'data' => true,
                'labels' => true,
                'size' =>  true,

                //optional
                'colors' => true,
                'fill' => array(
                    'type' => true,
                    'color' => true,
                    'angle' => true,
                    'offset' => true,
                ),//file
                'scale' => array(
                    0 => array(
                        'min' => true,
                        'max' => true
                    )
                ),//scale
                'title' =>array(
                    'text' => true,
                    'color' => true,
                    'size' => true
                ),//title
                'legend' => array(
                    'labels' => true,
                    'position' => array(
                        'horizontal' => true,
                        'vertical' => true
                    )
                ),//legend
                'orientation' => true
            )
        );

        var $chartTypes = array(
            //pie charts
                'pie2d'      => 'cht=p',
                'pie3d'      => 'cht=p3',
                'concentric' => 'cht=pc',
            //bar charts
                'bar'        => 'cht=bhs',

            //line charts
                'line'       => 'cht=lc',
                'spark'      => 'cht=ls',
                'compare'    => 'cht=lxy',

            // radar
                'radar'      => 'cht=r',
                //'radar_fill' => 'cht=rs',

            // other
                'scatter'    => 'cht=s',
                'venn'       => 'cht=v',

            // special
                'meter'      => 'cht=gom',
                'map'        => 'cht=t',
                'qr_code'    => 'cht=qr'
        );

        var $map = array(
            'data' => array(
                'code' => 'chd=t:',
                'seperator' => ','
            ),
            'labels' => array(
                'code' => 'chl=',
                'seperator' => '|'
            ),
            'size' => array(
                'code' => 'chs=',
                'seperator' => 'x'
            ),
            'colors' => array(
                'code' => '',
                'seperator' => ''
            ),
            'solid_fill' => array(
                'code' => 'chf=',
                'format' => '%s,%s,%s' //type,style,color
            ),
            'gradient_file' => array( //gradient and lines ... offset == width
                'code' => 'chf=',
                'format' => '%s,%s,%d,%s,%f' //type,style,angle,color,offset
            ),
            'special_fill' => array(
                'code' => '',
                'format' => ''
            ),
            'scale' => array(
                'code' => '',
                'seperator' => ''
            ),
            'title' => array(
                'code' => 'chtt=',
                'seperator' => '+'
            ),
            'legend' => array(
                'code' => '',
                'seperator' => ''
            )
        );

        var $errors = null;

        var $return = null;

        var $paramSeperator = '&';

        var $maxSize = 300000;

        var $apiUrl = 'http://chart.apis.google.com/chart?';

        function test( $name = 'pie3d' )
        {
            switch( $name )
            {
                case 'pie3d':
                    return '<img border="0" alt="Yellow pie chart" src="http://chart.apis.google.com/chart?chs=250x100&chd=t:60,40&cht=p3&chl=Hello|World"/>';
                    break;

                case 'pie':
                    ;
                    break;

                default:
                    ;
            } // switch
        }

        function display( $name = null, $data = array() )
        {
            if ( empty( $data ) )
            {
                return false;
            }

            $this->__setChartType( $name );

            foreach( $data as $key => $value )
            {
                pr( $key );
                if ( !isset( $this->setup[$name][$key] ) )
                {
                    $this->errors = __( 'Param "'.$key.'" is not supported in chart type "'.$name.'"', true );
                    continue;
                }

                switch( $key )
                {
                    case 'data':
                    case 'labels':
                        $this->__setData( $key, $value );
                        break;

                    case 'size':
                        $this->__setSize( $value );
                        break;

                    case 'title':
                        $this->__setTitle( $value );
                        break;

                } // switch
            }

            return $this->__render( $data );
        }

        function __setTitle( $title )
        {
            if ( is_array( $title ) )
            {

            }

            else
            {
                $title = str_replace( '<br/>', '|', $title );
                $this->__setData( 'title', explode( ' ', $title ) );
            }
        }

        function __render( $data )
        {
            $data['html'] = array();
            if ( !isset( $data['html']['title'] ) && isset( $data['title'] ) )
            {
                if ( !is_array( $data['title'] ) )
                {
                    $data['html']['title'] = $data['title'];
                }
                else if ( is_array( $data['title']['text'] ) )
                {
                    $data['html']['title'] = $data['title']['text'];
                }
            }

            return $this->Html->image(
                $this->apiUrl.implode( $this->paramSeperator,$this->return ),
                $data['html']
            );
        }

        function __setSize( $data )
        {
            if ( !is_array( $data ) )
            {
                $data = explode( ',', $data, 3 );
            }

            if ( $data[0] * $data[1] > $this->maxSize )
            {
                $this->erros[] = __( 'Sizes exceed the maximum for google charts api', true );
                $data = array( 100, 100 );
            }

            return $this->__setReturn( 'size', $data );
        }

        function __setData( $key, $data )
        {
            if ( !is_array( $data ) )
            {
                $data = explode( ',', $data );
            }

            return $this->__setReturn( $key, $data );
        }

        function __setReturn( $key, $data )
        {

            $return = $this->map[$key]['code'];

            if ( isset( $this->map[$key]['seperator'] ) )
            {
                $return .= implode( $this->map[$key]['seperator'], $data );
            }

            $this->return[] = $return;
            return true;

        }

        /**
         * ChartHelper::__setChart()
         *
         * checks that the name passed in is part of the valid charts that can be drawn.
         * saves the data to the $this->return
         *
         * @param string $name
         * @retrun bool
         */
        function __setChartType( $name )
        {
            if ( !in_array( $name, array_flip( $this->chartTypes ) ) )
            {
                $this->errors[] = __( 'Incorect chart type', true );
                return false;
            }

            $this->return[] = $this->chartTypes[$name];
            return true;
        }

        function __autoColor()
        {

        }

        function __autoScale()
        {

        }

        function encode( $data = array(), $type = 't' )
        {
            if ( !is_array( $data ) )
            {
                $data = array( $data );
            }
        }













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