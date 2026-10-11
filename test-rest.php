<?php
if ( PHP_SAPI !== 'cli' ) { http_response_code( 404 ); exit; } // Dev-only CLI script: it rewrites active_plugins, so it must never run from a web request (#5978, M10).
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../../../wp-load.php';
$active_plugins = get_option('active_plugins');

$bomb_bag_index = array_search('xophz-compass-bomb-bag/xophz-compass-bomb-bag.php', $active_plugins);
if ($bomb_bag_index !== false) {
    unset($active_plugins[$bomb_bag_index]);
}

update_option('active_plugins', array_values($active_plugins));
echo "Bomb bag removed.\n";
