<?php
/* $Id: server_import.php 7908 2005-11-24 09:12:17Z nijel $ */
// vim: expandtab sw=4 ts=4 sts=4:

require_once('./libs/common.lib.php');

/**
 * Does the common work
 */
require('./libs/server_common.inc.php');


/**
 * Displays the links
 */
require('./libs/server_links.inc.php');

$import_type = 'server';
require('./libs/display_import.lib.php');
/**
 * Displays the footer
 */
require('./libs/footer.inc.php');
?>

