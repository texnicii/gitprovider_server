<?php
(posix_getpwuid(posix_getuid())['name']=='root') or die("run as root\n");
@mkdir($workdir='/var/lib/pullserver/');
chmod($workdir, 0777);
if(is_dir($workdir))
	echo "done\n";
else
	echo "fail\n";
