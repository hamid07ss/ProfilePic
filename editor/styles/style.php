<?php
/*** set the content type header ***/
header( "Content-type: text/css" );

$fonts     = glob( '../fonts/*' );
$fontsCss  = '';
$fontsHtml = '';
foreach ( $fonts as $font ) {
	$fontsCss .= '
	@font-face {
	    font-family: ' . pathinfo( $font, PATHINFO_FILENAME ) . ';
	    src: url(' . $font . ') format(\'woff\');
	}	
	';
	$fontsHtml .= '<li style="font-family:' . pathinfo( $font, PATHINFO_FILENAME ) . ';font-weight: normal;">تست فونت => ' . pathinfo( $font, PATHINFO_FILENAME ) . ' </li>';
}

if ( isset( $_GET["fontsHtml"] ) ) {
	echo $fontsHtml;
} else {
	echo $fontsCss;
}


