<?php
/* $Id: db_sql.php 9602 2006-10-25 12:25:01Z nijel $ */
// vim: expandtab sw=4 ts=4 sts=4:

require_once('./libs/common.lib.php');

/**
 * Runs common work
 */
require('./libs/db_common.inc.php');
require_once './libs/sql_query_form.lib.php';

/**
 * Gets informations about the database and, if it is empty, move to the
 * "db_structure.php" script where table can be created
 */
require('./libs/db_info.inc.php');
if ( $num_tables == 0 && empty( $db_query_force ) ) {
    $sub_part   = '';
    $is_info    = TRUE;
    require './db_structure.php';
    exit();
}

/**
 * Query box, bookmark, insert data from textfile
 */
PMA_sqlQueryForm();

/**
 * Displays the footer
 */
require_once './libs/footer.inc.php';
?>
