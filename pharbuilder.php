<?php
error_reporting(-1);

(!empty($argv[1])&&!empty($argv[1])) or die("USAGE: ".basename(__FILE__)." filename project-dir [stub-file]\n");
$targetPharFilename=$argv[1];
$srcDir=preg_match('!^[\/]!', $argv[2])?$argv[2]:__DIR__.'/'.$argv[2];
$stubFile=$argv[3]??'index.php';

$phar = new Phar($targetPharFilename, 0, basename($targetPharFilename));
$phar->buildFromDirectory($srcDir, '/\.php$/');
$phar->setStub("#!/usr/bin/env php\n".$phar->createDefaultStub($stubFile));
chmod($targetPharFilename, 0755);