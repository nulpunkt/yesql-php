<?php
error_reporting(E_ALL | E_STRICT);

ini_set("memory_limit", "1024M"); // nothing to see here

set_include_path(__DIR__ . ":".get_include_path());

require_once __DIR__."/../vendor/autoload.php";
