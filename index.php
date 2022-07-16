<?php
/**
 * Plugin Name: Visual
 * Description: Adds <code>[visual]</code> shortcode for visual testing form.
 * Plugin URI: https://github.com/rtcamp/visual-automation-backstop
 * Version: 1
 * Author: rtCamp
 * Author URI: https://rtcamp.com
 */

namespace Visual;

if ( ! defined( 'ABSPATH') ) { exit; }

add_action(
	'init',
	function(){
		add_shortcode(
			'visual',
			function( $a ) {
				wp_enqueue_script( 'jquery' );

				ob_start();
				?>
<form id="visual-form">
	<input type="url" name="live" placeholder="https://site.live" />
	<input type="url" name="dev" placeholder="https://site.dev" />
	<button><?php \esc_html_e( 'Test', 'visual' ); ?></button>
</form>
<div id="visual-queue">
	<?php
	global $post;

	$q = new \WP_Query([
		'post_type' => 'visual',
		'post_status' => 'publish',
		'posts_per_page' => 100,
		'orderby' => 'ID',
		'order' => 'DESC',
	]);

	while( $q->have_posts() ) {
		$q->the_post();

		$status = get_post_meta( get_the_ID(), 'status', true );
		if ( empty( $status ) ) {
			$status = 'processing';
		}

		if ( 'processing' === $status ) {
			printf(
				'<article data-id="%d" data-status="%s"><a href="%s">%s: %s</a></article>',
				get_the_ID(),
				esc_attr( $status ),
				esc_url( get_the_permalink() ),
				esc_html( get_the_title() ),
				apply_filters( 'the_content', $post->post_content )
			);
		}else {
			$url = plugins_url( sprintf(
				'backstop_data/%s/html_report/index.html',
				get_post_meta( get_the_ID(), 'project_id', true )
			), __FILE__ );

			printf(
				'<article data-id="%d" data-status="%s">%s <a href="%s">%s</a></article>',
				get_the_ID(),
				esc_attr( $status ),
				get_the_title(),
				esc_url( $url  ),
				__( 'Report', 'visual' )
			);
		}
	}
	wp_reset_postdata();
	?>
</div>
				<?php
				return ob_get_clean();
			}
		);

		register_post_type( 'visual', [
			'labels'             => [
				'name'                  => _x( 'Visual', 'Post type general name', 'visual' ),
				'singular_name'         => _x( 'Visual', 'Post type singular name', 'visual' ),
				'menu_name'             => _x( 'Visuals', 'Admin Menu text', 'visual' ),
				'name_admin_bar'        => _x( 'Visual', 'Add New on Toolbar', 'visual' ),
				'add_new'               => __( 'Add New', 'visual' ),
				'add_new_item'          => __( 'Add New Visual', 'visual' ),
				'new_item'              => __( 'New Visual', 'visual' ),
				'edit_item'             => __( 'Edit Visual', 'visual' ),
				'view_item'             => __( 'View Visual', 'visual' ),
				'all_items'             => __( 'All Visuals', 'visual' ),
				'search_items'          => __( 'Search Visuals', 'visual' ),
				'parent_item_colon'     => __( 'Parent Visuals:', 'visual' ),
				'not_found'             => __( 'No Visuals found.', 'visual' ),
				'not_found_in_trash'    => __( 'No Visuals found in Trash.', 'visual' ),
				'featured_image'        => _x( 'Visual Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'visual' ),
				'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'visual' ),
				'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'visual' ),
				'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'visual' ),
				'archives'              => _x( 'Visual archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'visual' ),
				'insert_into_item'      => _x( 'Insert into Visual', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'visual' ),
				'uploaded_to_this_item' => _x( 'Uploaded to this Visual', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'visual' ),
				'filter_items_list'     => _x( 'Filter Visuals list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'visual' ),
				'items_list_navigation' => _x( 'Visual list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'visual' ),
				'items_list'            => _x( 'Visual list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'visual' ),
			],
			'description'        => __( 'Visual web site tests.', 'visual' ),
			'public'             => is_user_logged_in(),
			'publicly_queryable' => is_user_logged_in(),
			'exclude_from_search'=> ! current_user_can( 'edit_others_posts' ),
			'show_ui'            => current_user_can( 'edit_others_posts' ),
			'show_in_menu'       => current_user_can( 'edit_others_posts' ),
			'query_var'          => is_user_logged_in(),
			'rewrite'            => [ 'slug' => 'visual' ],
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 20,
			'supports'           => [ 'title', 'editor', 'author', 'thumbnail' ],
			'taxonomies'         => [],
			'show_in_rest'       => true,
			'rest_base'          => 'visual',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'show_in_graphql' => current_user_can( 'edit_others_posts' ),
			'graphql_single_name' => 'visual',
			'graphql_plural_name' => 'visuals',
		] );
	}
);

add_action(
	'wp_footer',
	function(){
		?>
<script>
jQuery(document).ready( function($){
	$('#visual-form').on('submit', function(e){
		e.preventDefault();

		let $pending_visual = $('<article>');

		$.ajax({
			method: 'POST',
			url:  '<?php echo esc_url_raw( rest_url( 'visual/v1/start-test' ) ); ?>',
			data: {
				live: $('#visual-form input[name="live"]').val(),
				dev: $('#visual-form input[name="dev"]').val()
			},
			beforeSend: function ( xhr ) {
				xhr.setRequestHeader( 'X-WP-Nonce', '<?php echo wp_create_nonce( 'wp_rest' ); ?>' );

				$pending_visual
					.text(
						$('#visual-form input[name="live"]').val() + ' <> ' + $('#visual-form input[name="dev"]').val()
					);

				$('#visual-queue').prepend( $pending_visual );

			},
			error: function( r ) {
				if ( 403 === r.status ) {
					alert( 'You are not currently logged in. Please refresh the page to log in again.' );
				} 
			},
			success : function( r ) {
				$pending_visual
					.html('')
					.attr('data-id', r.id )
					.attr('data-status', r.status )
					.append(
						$('<a>')
							.attr('href', r.permalink )
							.text( r.title )
					);
			}
		});
	});

	setInterval(
		function(){
			$('#visual-queue article[data-status="processing"]').each( function( index ){
				let $article = $(this);
				$.ajax({
					method: 'GET',
					url:  '<?php echo esc_url_raw( rest_url( 'wp/v2/visual/' ) ); ?>' + $(this).attr('data-id'),
					data: {},
					beforeSend: function ( xhr ) {
						xhr.setRequestHeader( 'X-WP-Nonce', '<?php echo wp_create_nonce( 'wp_rest' ); ?>' );
					},
					error: function( r ) {
						if ( 403 === r.status ) {
							alert( 'You are not currently logged in. Please refresh the page to log in again.' );
						} 
					},
					success : function( r ) {
						if ( -1 !== r.content.rendered.indexOf('Report') ) {
							$article
								.html( r.title.rendered + ': ' + r.content.rendered )
								.attr( 'data-status', 'complete' );
						}else {
							$article.find('a').text( r.title.rendered + ': ' + r.content.rendered );
						}
					}
				});
			} );
		},
		6000
	);
});
</script>
		<?php
	},
	1000
);

add_action(
	'rest_api_init',
	function(){
		\register_rest_route(
			'visual/v1',
			'/start-test',
			[
				'methods' => [ 'GET', 'POST' ],
				'callback' => function( $request ) {
					global $post;

					$live_url = $request->get_param( 'live' );
					$dev_url = $request->get_param( 'dev' );
					$post_title = $live_url . ' <> ' . $dev_url;

					$id = \wp_insert_post( [
						'post_title' => $post_title,
						'post_content' => '',
						'post_type' => 'visual',
						'post_status' => 'publish',
					] );

					$project_id = $id . '-' . sanitize_file_name( parse_url( $live_url, PHP_URL_HOST ) );

					update_post_meta( $id, 'live_url', esc_url_raw( $live_url ) );
					update_post_meta( $id, 'dev_url', esc_url_raw( $dev_url ) );
					update_post_meta( $id, 'project_id', $project_id );
					update_post_meta( $id, 'status', 'processing' );

					sleep(1);

					\clean_post_cache( $id );
					$post = get_post( $id );
					\setup_postdata( $post );

					add_action(
						'shutdown',
						function() use ( $post ) {
							xml_sitemap_to_csv( $post );
							exit;
						}
					);

					return [
						'id' => intval( $post->ID ),
						'title' => $post->post_title,
						'permalink' => \get_permalink( $post->ID ),
						'status' => \get_post_meta( $post->ID, 'status', true ),
					];
				},
			]
		);
	}
);

function xml_sitemap_to_csv( $post ) {
	$project_id = get_post_meta( $post->ID, 'project_id', true );
	$live_url = get_post_meta( $post->ID, 'live_url', true );
	$dev_url = get_post_meta( $post->ID, 'dev_url', true );

	if ( ! file_exists( __DIR__ . '/data/' . $project_id . '.csv' ) ) {
		$urls = [];

		// @todo: convert to wp_remote_get() and check for 404.
		$sitemap = file_get_contents(
			trim( esc_url_raw( $live_url ), '/ ' ) . '/wp-sitemap.xml' 
		);

		$sitemap_dom = new \DomDocument();
		@$sitemap_dom->loadHTML(
			mb_convert_encoding( $sitemap, 'HTML-ENTITIES', 'UTF-8' )
		);

		try{
			foreach( $sitemap_dom->getElementsByTagName('loc') as $s ) {
				$sub_sitemap = file_get_contents( esc_url_raw( $s->textContent ) );

				$sub_sitemap_dom = new \DomDocument();
				@$sub_sitemap_dom->loadHTML(
					mb_convert_encoding( $sub_sitemap, 'HTML-ENTITIES', 'UTF-8' )
				);

				foreach( $sub_sitemap_dom->getElementsByTagName('loc') as $l ) {

					if ( in_array(
						pathinfo( $l->textContent, PATHINFO_EXTENSION ),
						[ 'jpg', 'jpeg', 'png', 'gif', 'svg', 'mp4' ]
					) ) {
						continue;
					}

					$urls[] = $l->textContent;
				}
			}
		}catch( \Expression $e ) {

		}

		$fp = fopen( __DIR__ . '/data/' . $project_id . '.csv', 'w');

		fputcsv($fp, [
			'label','referenceUrl','url',
		]);

		foreach ($urls as $key => $url) {
			if ( $key > 10 ) { continue; } // limit URL count for shorter test runs.
			fputcsv($fp, [
				sanitize_title( $url ),
				esc_url_raw( $url ),
				esc_url_raw( str_replace(
					$live_url,
					$dev_url,
					$url
				) )
			]);
		}
		fclose($fp);
	}
}

add_action(
	'wp_ajax_nopriv_visual-start-reference',
	function(){
		if ( $_GET['key'] !== md5( md5_file( __FILE__ ) . date( 'Y-m-d' ) ) ) {
			return;
		}
		$post = get_post( (int) $_GET['id'] );
		start_backstop_reference( $post );
		exit;
	}
);
add_action(
	'wp_ajax_nopriv_visual-start-test',
	function(){
		if ( $_GET['key'] !== md5( md5_file( __FILE__ ) . date( 'Y-m-d' ) ) ) {
			return;
		}
		$post = get_post( (int) $_GET['id'] );
		start_backstop_test( $post );
		exit;
	}
);

function start_backstop_reference( $post ) {
	$prefix = 'export PATH="/opt/homebrew/bin/:$PATH" && cd "' . __DIR__ . '" && '; // Assume node installed with homebrew.

	$project_id = get_post_meta( $post->ID, 'project_id', true );

	$command =  sprintf(
		'%s node ./bin/csv-parser.js --project_id="%s" && backstop reference --config=test/mainConfigCSV.js --project_id="%s"',
		$prefix,
		$project_id,
		$project_id
	);
	$output = shell_exec( $command );
}

function start_backstop_test( $post ) {
	$project_id = get_post_meta( $post->ID, 'project_id', true );

	if ( ! is_dir(
		sprintf(
			'%s/backstop_data/%s/bitmaps_test',
			__DIR__,
			$project_id
		)
	) ) {
		$prefix = 'export PATH="/opt/homebrew/bin/:$PATH" && cd "' . __DIR__ . '" &&'; // Assume node installed with homebrew.

		$command = sprintf(
			'%s backstop test --config=test/mainConfigCSV.js --project_id="%s"',
			$prefix,
			$project_id
		);
		$output = shell_exec( $command );

		return false;
	}else {
		return true;
	}

}

add_filter(
	'the_content',
	function( $c ) {
		global $post;

		if ( 'visual' !== get_post_type() ) {
			return $c;
		}
		$status = get_post_meta( get_the_ID(), 'status', true );
		$project_id = get_post_meta( get_the_ID(), 'project_id', true );

		if ( 'processing' === $status ) {
			if ( ! is_dir(
				sprintf(
					'%s/backstop_data/%s/bitmaps_reference',
					__DIR__,
					$project_id
				)
			) ) {
				// @see https://wordpress.stackexchange.com/questions/180131/call-function-without-having-to-wait-on-response
				wp_remote_get(
					add_query_arg(
						[
							'action' => 'visual-start-reference',
							'id' => get_the_ID(),
							'key' => md5( md5_file( __FILE__ ) . date( 'Y-m-d' ) ),
						],
						admin_url( 'admin-ajax.php' )
					),
					[
						'timeout'   => 0.01,
						'blocking'  => false,
						'sslverify' => false,
					]
				);
				return 'Starting reference tests...';
			}

			if ( ! is_dir(
				sprintf(
					'%s/backstop_data/%s/html_report',
					__DIR__,
					$project_id
				)
			) ) {
				// Current files
				$backstop_files_in_progress = glob(
					sprintf(
						'%s/backstop_data/%s/bitmaps_reference/*',
						__DIR__,
						$project_id
					)
				);

				$backstop_test_files_in_progress = glob(
					sprintf(
						'%s/backstop_data/%s/bitmaps_test/*/*',
						__DIR__,
						$project_id
					)
				);

				// Expected files
				$csv_file = __DIR__ . '/data/' . $project_id . '.csv';
				$file = new \SplFileObject( $csv_file, 'r');
				$file->seek( PHP_INT_MAX );
				$rows_in_csv = $file->key() - 1;
				$expected_files = $rows_in_csv * 3; // 3 === Number of variations set in backstop. @todo get from config.

				if (
					$expected_files === count( $backstop_files_in_progress )
					&& ! is_dir(
						sprintf(
							'%s/backstop_data/%s/bitmaps_test',
							__DIR__,
							$project_id
						)
					)
				) {
					wp_remote_get(
						add_query_arg(
							[
								'action' => 'visual-start-test',
								'id' => get_the_ID(),
								'key' => md5( md5_file( __FILE__ ) . date( 'Y-m-d' ) ),
							],
							admin_url( 'admin-ajax.php' )
						),
						[
							'timeout'   => 0.01,
							'blocking'  => false,
							'sslverify' => false,
						]
					);
				}
			}else {
				update_post_meta( get_the_ID(), 'status', 'complete' );

				$url = plugins_url( sprintf(
					'backstop_data/%s/html_report/index.html',
					$project_id
				), __FILE__ );

				return sprintf(
					'<a href="%s">%s</a>',
					esc_url( $url  ),
					__( 'Report', 'visual' )
				);
			}

			return sprintf(
				'%s of %s references processed. %s of %s comparisons processed.',
				number_format( count( $backstop_files_in_progress ) ),
				number_format( $expected_files ),
				number_format( count( $backstop_test_files_in_progress ) ),
				number_format( $expected_files )
			) . $c;
		}else {
			$url = plugins_url( sprintf(
				'backstop_data/%s/html_report/index.html',
				$project_id
			), __FILE__ );

			return sprintf(
				'<a href="%s">%s</a>',
				esc_url( $url  ),
				__( 'Report', 'visual' )
			);
		}

		return $c;
	}
);