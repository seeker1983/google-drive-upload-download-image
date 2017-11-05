<?php

function upload_file_gd($_path, $local_file, $file_name = false, $overwrite = true) {
	/* Generate remote name if not specified */
	if(empty($file_name)) {
		$file_name = uniqid() . '.png';
	}

	/* Split path into nodes */
	$path = explode('/', trim($_path, '/'));

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
		    Logger::log('Creating remote folder ' . $folder);

			$fileMetadata = new Google_Service_Drive_DriveFile(array(
		    	'name' => $folder,
		    	'parents' => array($parent),
		    	'mimeType' => 'application/vnd.google-apps.folder'
		    ));

			$new_folder = $service->files->create($fileMetadata, array(
		    	'fields' => 'id')
		    );

		    $parent = $new_folder->getId();
		} else {
			$parent = $results->getFiles()[0]->getId();
		}

	}
	Logger::log('Uploading file ' . $file_name);

	if($overwrite) {
		$results = $service->files->listFiles([
			'pageSize' => 10, 
			'q' => sprintf("trashed != true and name='%s' and '%s' in parents and mimeType != 'application/vnd.google-apps.folder'", $file_name, $parent)
		]);
		if(count($results->getFiles()) > 0) {
		    Logger::log(sprintf('Remote file %s exists, deleting... ', $file_name));

		    foreach ($results->getFiles() as $file) {
				$service->files->delete($file->getId());
		    }
		}
	}

	/* Prepate file */
	$fileMetadata = new Google_Service_Drive_DriveFile(array(
	    'name' => $file_name,
	    'parents' => array($parent)
	));

	/* Upload file */
	$file = $service->files->create($fileMetadata, array(
    	'data' => file_get_contents($local_file),
    	'mimeType' => 'image/jpeg',
    	'uploadType' => 'multipart',
    	'fields' => 'id,webContentLink'));

	/* If success, return link */
	if ($file) {

		if(UPLOAD_ANYONE_WITH_LINK) {
		   /* If requested, set permissions to anyone with link*/
		    $permissionMetadata = new Google_Service_Drive_Permission(array(
		        'type' => 'anyone',
		        'role' => 'reader',
		        'value' => 'default'
		    ));

		    $permission = $service->permissions->create(
		        $file->getId(), $permissionMetadata, array('fields' => 'id'));
				
		}

	   /* Return link */

	    Logger::log('Created file id ' . $file->getId());

	    return $file->getWebContentLink() . "&name=/" . trim($_path, '/') . "/" . $file_name;
	} else {
		Logger::log('Upload error ' . var_export($file, true));
	}
}




