<?php
/*
* several functions that create html anchor tags
*/

function toKeyword( string $keyword, $text = false, $title = false ){
	/*
	* create a archive query link of any string
	*/
	$keyword = trim( $keyword, " -,.;+\t\n\r\0\x0B");
	if( !$keyword ){
		return;
	}
	$text = ucfirst( r( $text, $text, $keyword ) );
	$title = ucfirst( r( $title, $title, $keyword ) );
	return Html::a(
		kirby()->page('archive')->url().'?q='.Str::slug( $keyword ),
		$text,
		$attr = [
			'title' => 'Research "'.$title.'"',
			'rel' => 'nofollow'
	]);
}

function toKeywords( $terms, string $delimiter = ',', string $glue = ', ' ){
	/*
	* create a list of archive query links
	* $terms can be array or string
	*/
	if( is_string($terms) ){
		$terms = explode( $delimiter, $terms );
	}
	$return = [];
	foreach ($terms as $term) {
		$return[] = toKeyword( $term );
	}
	return implode( $glue, $return );
}

function toLink( $url, $text = false ){
	/*
	* creates an external weblink
	*/
	if( !$text ){
		$host = parse_url( $url );
		if( isset( $host['host'] ) ){
			$text = $host;
		} else {
			$text = $url;
		}
	}
	return Html::a(
		$url,
		$text,
		$attr = [
			'target' => '_blank',
			'title' => 'Open "'.$url.'"'
	]);
}

function toPageOrKeyword( string $id ){

	$kirby = kirby();

	if( $page = $kirby->page( $id ) ){

		return $page->toLink();

	} else if ( $image = $kirby->file( $id ) ){

		return $image = $image->toLink;

	} else {

		return toKeyword( $id );

	}

}

function toUserOrKeyword( string $id ){

	/*
	* currently there are no user pages
	*/
	return toKeyword( $id );

	if( $user = kirby()->users()->search( $id )->first() ){

		return $user->toLink();

	} else {

		return toKeyword( $id );

	}

}