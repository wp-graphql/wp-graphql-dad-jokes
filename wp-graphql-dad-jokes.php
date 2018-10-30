<?php
/**
 * Plugin Name:     WPGraphql Dad Jokes
 * Plugin URI:      http://github.com/wp-graphql/wp-graphql-dad-jokes
 * Description:     A WPGraphQL Extension that adds a root query to the GraphQL schema that returns a random Dad Joke from icanhazdadjokes.com
 * Author:          WPGraphQL, Jason Bahl
 * Author URI:      http://graphql.com
 * Text Domain:     wp-graphql-dad-jokes
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         WP_Graphql_Dad_Jokes
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'graphql_register_types', function() {

	register_graphql_field( 'RootQuery', 'dadJoke', [
		'type' => 'String',
		'description' => __( 'Returns a random Dad joke', 'wp-graphql' ),
		'resolve' => function() {
			$get_dad_joke = wp_remote_get('https://icanhazdadjoke.com/', [
				'headers' => [
					'Accept' => 'application/json',
					'User-Agent' => 'WPGraphQL Dad Jokes (https://github.com/wp-graphql/wp-graphql-dad-jokes)',
				],
			] );
			$body = ! empty( $get_dad_joke['body'] ) ?  json_decode( $get_dad_joke['body'] ) : null;
			$joke = ! empty( $body->joke ) ? $body->joke : null;
			return $joke;
		},
	]);

} );
