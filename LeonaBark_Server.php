<?php
error_reporting(E_ALL);

/* Allow the script to hang around waiting for connections. */
set_time_limit(0);

/* Turn on implicit output flushing so we see what we're getting
 * as it comes in. */
ob_implicit_flush();

$address = '192.168.1.109';
$port = 10002;

if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
}

if (socket_bind($sock, $address, $port) === false) {
    echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
}

if (socket_listen($sock, 5) === false) {
    echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
}

do {
    if (($msgsock = socket_accept($sock)) === false) {
        echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
        break;
    }

	if (false === ($buf = socket_read($msgsock, 2048))) {
            echo "socket_read() failed: reason: " . socket_strerror(socket_last_error($msgsock)) . "\n";
            break 2;
        }
	echo "Unknown command: " . str_replace("\r\n", '\r\n', $buf) ."\n";
	
	switch ($buf) {
		case 'scream1':
			exec('afplay man-scream.mp3');
			break;
		case 'leonaNo':
			exec('afplay 嬛嬛no.m4a');
			break;
		default:
			exec('afplay message.mp3');
			break;
		}

    /* Send instructions. */
    $msg = "Connection built!";
	$msg .=" Buf= ".$buf;

    socket_write($msgsock, $msg, strlen($msg));
    socket_close($msgsock);
} while (true);
socket_close($sock);
?>