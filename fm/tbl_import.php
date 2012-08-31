<?php
/* $Id: tbl_import.php 9601 2006-10-25 10:55:20Z nijel $ */
// vim: expandtab sw=4 ts=4 sts=4:

require_once('./libs/common.lib.php');

/**
 * Gets tables informations and displays top links
 */
require_once('./libs/tbl_common.php');
$url_query .= '&amp;goto=tbl_import.php&amp;back=tbl_import.php';

require_once('./libs/tbl_info.inc.php');
/**
 * Displays top menu links
 */
require_once('./libs/tbl_links.inc.php');

$import_type = 'table';
require_once('./libs/display_import.lib.php');

/**
 * Displays the footer
 */
require_once('./libs/footer.inc.php');
?>

