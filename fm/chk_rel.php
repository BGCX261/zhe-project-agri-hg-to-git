<?php
/* $Id: chk_rel.php 9602 2006-10-25 12:25:01Z nijel $ */
// vim: expandtab sw=4 ts=4 sts=4:


/**
 * Gets some core libs
 */
require_once('./libs/common.lib.php');
require_once('./libs/db_common.inc.php');
require_once('./libs/relation.lib.php');


/**
 * Gets the relation settings
 */
$cfgRelation = PMA_getRelationsParam(TRUE);


/**
 * Displays the footer
 */
require_once('./libs/footer.inc.php');
?>
