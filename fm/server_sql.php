<?php
/* $Id: server_sql.php 7908 2005-11-24 09:12:17Z nijel $ */
// vim: expandtab sw=4 ts=4 sts=4:

require_once('./libs/common.lib.php');

/**
 * Does the common work
 */
$js_to_run = 'functions.js';
require_once './libs/server_common.inc.php';
require_once './libs/sql_query_form.lib.php';


/**
 * Displays the links
 */
require './libs/server_links.inc.php';


/**
 * Query box, bookmark, insert data from textfile
 */
PMA_sqlQueryForm();

/**
 * Displays the footer
 */
require_once './libs/footer.inc.php';
?>
