<?php
require_once __DIR__.'/vendor/autoload.php';

define('PID_FILE', '/var/lib/pullserver/pullserver.pid');
define('SOCKET_FILE', '/var/lib/pullserver/pullserver.sock');

if(file_exists(PID_FILE))
	if($pid=file_get_contents(PID_FILE))
		if(trim(`ps $pid|grep -v PID`)!='')
			die("Already running\n");

@unlink(SOCKET_FILE);
$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server('unix://'.SOCKET_FILE, $loop);
chmod(SOCKET_FILE, 0777);
file_put_contents(PID_FILE, getmypid());

$socket->on('connection', function (React\Socket\ConnectionInterface $connection) {
	$connection->write("Welcome to Sitebuilder update provider!\n");
	$connection->on('data', function ($data) use ($connection) {
		if(!is_object($command=json_decode($data)))
			$connection->write("Unknown command. Use json format.");
		else{
			$shout=shell_exec("cd '{$command->dir}' && git {$command->gitact} 2>&1");
			$connection->write($shout);
		}
		$connection->end();
	});
});

$loop->run();