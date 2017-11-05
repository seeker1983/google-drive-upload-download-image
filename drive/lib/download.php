<?php

function download_file_gd($remote_file, $local_name) {
	/* Generate remote name if not specified */

	/* Split path into nodes */
	$path = explode('/', trim(dirname($remote_file), '/'));

	/* Get file name */
	$file_name = basename($remote_file);

	/* Initialize google drive service */
	$service = create_drive_service();

	/* Start from root folder */
	$parent = 'root';

	/* Navigate to required folder */
	while($folder = array_shift($path)) {
		Logger::log('Entering ' . $folder);
		$results = $service->files->listFiles([
			'pageSize' => 10, 
			'q' => sprintf("trashed != true and name='%s' and '%s' in parents and mimeType = 'application/vnd.google-apps.folder'", $folder, $parent)
		]);

		if (count($results->getFiles()) == 0) {
	  		Logger::log('Remote folder ' . REMOTE_FOLDER . ' not found');
	  		return false;
		} else {
			$parent = $results->getFiles()[0]->getId();
		}

	}
	$results = $service->files->listFiles([
		'pageSize' => 10, 
		'q' => sprintf("'%s' in parents and name='%s'", $results->getFiles()[0]->getId(), $file_name)
	]);

	if (count($results->getFiles()) == 0) {
  	  	Logger::log('Remote file %s not found in %s', $file_name, dirname($remote_file));
	  	return false;
	} else {
		Logger::log('Downloading file ' . $file_name);

		$file = $results->getFiles()[0];

		$response = $service->files->get($file->getId(), ['alt' => 'media']);
	
		if($response->getStatusCode() == 200) {
			Logger::log($file->getName() . ' downloaded successfully');

			file_put_contents($local_name, $response->getBody()->getContents());

		  	return true;
		} else {
    		Logger::log(sprintf("Error %s downloading %s.\nServer response:\n%s.",
				$response->getStatusCode(), $file->getName(), $response->getBody()->getContents()));
		  	return false;
		}

	}

}




