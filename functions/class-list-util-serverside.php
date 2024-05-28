<?php
function filterArray( $array, $allowed = [] ) {
	return array_filter(
		$array,
		function ( $val, $key ) use ( $allowed ) { // N.b. $val, $key not $key, $val
			return isset( $allowed[ $key ] ) && ( $allowed[ $key ] === true || $allowed[ $key ] === $val );
		},
		ARRAY_FILTER_USE_BOTH
	);
}

function filterKeyword( $data, $search, $field = '' ) {
	$filter = '';
	if ( isset( $search['value'] ) ) {
		$filter = $search['value'];
	}
	if ( ! empty( $filter ) ) {
		if ( ! empty( $field ) ) {
			if ( strpos( strtolower( $field ), 'date' ) !== false ) {
				// filter by date range
				$data = filterByDateRange( $data, $filter, $field );
			} else {
				// filter by column
				$data = array_filter( $data, function ( $a ) use ( $field, $filter ) {
					return (boolean) preg_match( "/$filter/i", $a[ $field ] );
				} );
			}

		} else {
			// general filter
			$data = array_filter( $data, function ( $a ) use ( $filter ) {
				// echo strpos($filter, '/');exit;
				if (strpos($filter, '/') > 0)
				{
					return preg_match('~"'.$filter.'"~i', json_encode($a, JSON_UNESCAPED_SLASHES));
				}
				else
				{
					return (boolean) preg_grep( "/$filter/i", (array) $a );
				}
			} );
		}
	}

	return $data;
}

function filterByDateRange( $data, $filter, $field ) {
	// filter by range
	if ( ! empty( $range = array_filter( explode( '|', $filter ) ) ) ) {
		$filter = $range;
	}

	if ( is_array( $filter ) ) {
		foreach ( $filter as &$date ) {
			// hardcoded date format
			$date = date_create_from_format( 'm/d/Y', stripcslashes( $date ) );
		}
		// filter by date range
		$data = array_filter( $data, function ( $a ) use ( $field, $filter ) {
			// hardcoded date format
			$current = date_create_from_format( 'm/d/Y', $a[ $field ] );
			$from    = $filter[0];
			$to      = $filter[1];
			if ( $from <= $current && $to >= $current ) {
				return true;
			}

			return false;
		} );
	}

	return $data;
}
?>