<?php

/* Load libraries */
foreach (glob(__DIR__. '/drive/lib/*.php') as $lib) {
	include($lib);
}

$remote_path = '/images/nature/mountains';

$file_to_upload = __DIR__ . '/image.jpg';

$remote_name = 'river.jpg';

/* Upload file */
$link = upload_file_gd($remote_path, $file_to_upload, $remote_name);

if($link){
	Logger::log(sprintf('Upload successful. Link: <a href="%s">%s</a>', $link, $link));

	/* Download file */
	$remote_file = '/images/nature/mountains/river.jpg';
	$local_name = 'download/river-new.jpg';
	
	download_file_gd($remote_file, $local_name);
}


