<?php
define('IN_CB', true);
require_once('function.php');
registerImageKey('code', 'BCGcode39');
echo convertText('test');