<?php
/**
 * WPML-plugin compatibility.
 *
 * @package   Cherry Portfolio
 * @author    Cherry Team
 * @license   GPL-2.0+
 * @link      http://www.cherryframework.com/
 * @copyright 2012 - 2015, Cherry Team
 */

add_filter( 'cherry_portfolio_option_portfolio-category-list', 'cherry_portfolio_get_wpml_translated_category', 99 );
add_filter( 'cherry_portfolio_option_portfolio-tags-list', 'cherry_portfolio_get_wpml_translated_tag', 99 );

/**
 * Call a function for a retrieve a translated via WPML-plugin set of categories.
 *
 * @since  1.0.4.2
 * @param  array $categories Set of `Portfolio Category` slugs.
 * @return array
 */
function cherry_portfolio_get_wpml_translated_category( $categories ) {
	return cherry_portfolio_get_wpml_translated_term_slug( $categories, CHERRY_PORTFOLIO_NAME . '_category' );
}

/**
 * Call a function for a retrieve a translated via WPML-plugin set of tags.
 *
 * @since  1.0.4.2
 * @param  array $tags Set of `Portfolio Tags` slugs.
 * @return array
 */
function cherry_portfolio_get_wpml_translated_tag( $tags ) {
	return cherry_portfolio_get_wpml_translated_term_slug( $tags, CHERRY_PORTFOLIO_NAME . '_tag' );
}

/**
 * Retrieve a translated via WPML-plugin set of terms.
 *
 * @since  1.0.4.2
 * @param  array  $values   Set of a taxonomy slugs.
 * @param  string $taxonomy Taxonomy name.
 * @return array
 */
function cherry_portfolio_get_wpml_translated_term_slug( $values, $taxonomy ) {
	$terms_translated_ID = array();

	foreach ( ( array ) $values as $slug ) {
		$term = get_term_by( 'slug', $slug, $taxonomy );

		if ( ! empty( $term ) && is_object( $term ) ) {
			$terms_translated_ID[] = icl_object_id( $term->term_id, $taxonomy, false );
		}
	}

	if ( empty( $terms_translated_ID ) ) {
		return $values;
	}

	$terms_translated_slug = array();

	foreach ( $terms_translated_ID as $term_ID ) {

		if ( null === $term_ID ) {
			continue;
		}

		$term = get_term_by( 'id', $term_ID, $taxonomy );

		if ( ! empty( $term ) && is_object( $term ) ) {
			$terms_translated_slug[] = $term->slug;
		}
	}

	if ( ! empty( $terms_translated_slug ) ) {
		return $terms_translated_slug;
	}

	return $values;
}
