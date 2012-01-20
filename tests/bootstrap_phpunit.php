<?php

define('MIGRATIONENV','test');

# Project needs to be a global for our unit tests
global $project;

$project = require __DIR__.'/../src/Migration/Bootstrap.php';
