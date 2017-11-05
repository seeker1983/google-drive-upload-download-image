<?php

date_default_timezone_set(TIMEZONE);

class Logger {
	public static function log($data) {
		echo($data . ( php_sapi_name() == 'cli' ? PHP_EOL : '<BR>'));


		if($fp = fopen(LOG_FILE, 'a')) {
			fwrite($fp, date('d.m.Y H:i:s') . ' -> ' .$data . PHP_EOL);
			fclose($fp);
		}

	}
}

