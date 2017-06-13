<?php
/**
 * Created by PhpStorm.
 * User: M.Azadi <mohammad.azadi@yahoo.com>
 * Date: 3/1/2017
 * Time: 2:06 PM
 */
$tst = '{
	"date": "2017-03-01 11:01:37.000000",
          "timezone_type": 3,
          "timezone": "UTC"
        }';
$tst = json_decode( $tst );

$dt2 = new DateTime();
$dt2->setTimestamp( strtotime( '2017-03-1 12:00:00.000000' ) );

$dt = new DateTime();
$dt->setTimestamp(strtotime( '2017-03-2 02:00:00.000000' ));
print_r( strtotime( '2017-03-1 12:00:00.000000' ) );
echo '<br />';
print_r( ( $dt->format('d') != ( new DateTime() )->format( 'd' )) ? 'true' : 'false' );
echo '<br />';
print_r( $dt2->diff( $dt )->format( '%a' ) );

$datetime1 = new DateTime($tst);
print_r( $datetime1);
print_r( $datetime1);