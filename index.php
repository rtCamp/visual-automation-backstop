<form action="/" method="GET">
	<input type="url" name="live" value="<?php echo esc_url_raw( $_GET['live'] ); ?>" placeholder="https://site.live" />
	<input type="url" name="dev" value="<?php echo esc_url_raw( $_GET['dev'] ); ?>" placeholder="https://site.dev" />
	<button>Test</button>
</form>
<?php

// Each of the items below should be an individual AJAX call with a wrapper that monitors progress.
set_time_limit( -1 );

if ( empty( $_GET['live'] ) || empty( $_GET['dev'] ) ) {
	return;
}

// XML Sitemap to CSV
if ( ! file_exists( __DIR__ . '/data/' . $project_id . '.csv' ) ) {
	$urls = [];

	$sitemap = file_get_contents(
		trim( esc_url_raw( $_GET['live'] ), '/ ' ) . '/wp-sitemap.xml' 
	);

	$sitemap_dom = new \DomDocument();
	@$sitemap_dom->loadHTML(
		mb_convert_encoding( $sitemap, 'HTML-ENTITIES', 'UTF-8' )
	);

	try{
		foreach( $sitemap_dom->getElementsByTagName('loc') as $s ) {
			$sub_sitemap = file_get_contents( $s->textContent );

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
}

$project_id = 'project-' . date('n-j-Y');

$fp = fopen( __DIR__ . '/data/' . $project_id . '.csv', 'w');

fputcsv($fp, [
	'label','referenceUrl','url',
]);

foreach ($urls as $url) {
    fputcsv($fp, [
    	md5( $url ),
    	$url,
    	str_replace(
    		$_GET['live'],
    		$_GET['dev'],
    		$url
    	)
    ]);
}
fclose($fp);

// Backstop test

$prefix = 'export PATH="/opt/homebrew/bin/:$PATH" && cd ' . __DIR__ . ' && ';

$command = $prefix . 'npm run reference:csv 2>&1';
$output = shell_exec( $command );

echo '<pre>';
print_r( $command );
print_r( $output );
echo '</pre>';

foreach ( (array) explode( PHP_EOL, $output ) as $line ) {
	if ( false !== strpos( $line, 'ProjectID|' ) ) {
		$project_id = explode( '|', $line )[1];
	}
}

sleep( 1 );

// Backstop reference

$command = $prefix . 'npm run test:csv 2>&1';
$output = shell_exec( $command );

echo '<pre>';
print_r( $command );
print_r( $output );
echo '</pre>';

$url = sprintf(
	'%s://%s/backstop_data/%s/html_report/index.html',
	'on' === $_SERVER['HTTPS'] ? 'https' : 'http',
	$_SERVER['HTTP_HOST'],
	$project_id
);
printf(
	'<a href="%s">%s</a>',
	$url,
	$url
);

function esc_url_raw( $url ) {
	return $url; // No proper escaping anywhere in this file.
}