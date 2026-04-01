<?php
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
