<?php

require_once('../lib/util/HeaderUtil.class.php');
require_once('../lib/util/FileUtil.class.php');

@header('Content-type: text/css');

HeaderUtil::compressOutput();

echo FileUtil::mergeFiles(array('freeow.min.css', 'slide.min.css', 'style.css'))

?>
