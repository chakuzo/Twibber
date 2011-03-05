<?php

// Reports everything on world
error_reporting(E_ALL | E_STRICT | E_NOTICE | E_WARNING);

// Base
require_once('config.inc.php');
require_once(TWIBBER_DIR . '/lang/' . TWIBBER_LANG . '.lang.php');

// starting core
require_once(TWIBBER_DIR . '/lib/system/Twibber.class.php');

// Send header
HeaderUtil::sendHeaders();

?>