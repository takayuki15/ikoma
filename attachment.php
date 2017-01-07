<?php
if ( ( $url = wp_get_attachment_url( get_the_ID() ) ) ) {
	header( sprintf( 'Location: %s', $url ) );
} else
	header( sprintf( 'Location: %s', home_url() ) );
?>