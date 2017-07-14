<?php

class TZ_Helper
{
	public static function tz_wcwl_object_id( $id ){
		if( function_exists( 'wpml_object_id_filter' ) ){
			return wpml_object_id_filter( $id, 'page', true );
		}
		elseif( function_exists( 'icl_object_id' ) ){
			return icl_object_id( $id, 'page', true );
		}
		else{
			return $id;
		}
	}
}