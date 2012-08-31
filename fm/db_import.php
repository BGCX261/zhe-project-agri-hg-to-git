<?php
/* $Id: db_import.php 9602 2006-10-25 12:25:01Z nijel $ */
// vim: expandtab sw=4 ts=4 sts=4:

require_once('./libs/common.lib.php');

/**
 * Gets tables informations and displays top links
 */
require('./libs/db_common.inc.php');
require('./libs/db_info.inc.php');

$import_type = 'database';
require('./libs/display_import.lib.php');

/**
 * Displays the footer
 */
require('./libs/footer.inc.php');
?>

