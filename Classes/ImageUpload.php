<?php

namespace Classes;

class ImageUpload
{
    private $image;
    private $folder;
    private $generate_image_name;
    private $image_extension;
    private $target_file;
    private $upload_success;
    private $error_message; // Property to store error messages
    private static $rootFolderName = "/Recipe_Sharing_Web/"; // Change the name if necessary

    public function __construct($file, $fileFolder)
    {
        $this->image = $file;
        $this->folder = $fileFolder;
        $this->error_message = ""; // Initialize error message
        $this->upload_success = $this->UploadImg(); // Store the result of the upload
    }

    private function UploadImg()
    {
        if (!isset($this->image) || $this->image["error"] != UPLOAD_ERR_OK) {
            $this->error_message = "No file was uploaded or there was an upload error.";
            return false;
        }

        // Set the custom name with a unique generated ID for the image
        $this->generate_image_name = uniqid();
        $this->image_extension = strtolower(pathinfo($this->image['name'], PATHINFO_EXTENSION));
        $this->target_file = $this->folder . $this->generate_image_name . "." . $this->image_extension;

        // Check if the image is real
        if (!file_exists($this->image["tmp_name"]) || getimagesize($this->image["tmp_name"]) === false) {
            $this->error_message = "File is not an image.";
            return false;
        }

        // Check if the file already exists
        if (file_exists($this->target_file)) {
            $this->error_message = "Sorry, file already exists.";
            return false;
        }

        // Limit the size of the image to be uploaded
        if ($this->image["size"] > 5000000) { // 5MB
            $this->error_message = "Your file is too large.";
            return false;
        }

        // Allow certain file types
        if (!in_array($this->image_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            $this->error_message = "File type not supported.";
            return false;
        }

        // Ensure the target folder exists
        if (!is_dir($this->folder)) {
            mkdir($this->folder, 0777, true);
        }

        // Move the uploaded file
        if (move_uploaded_file($this->image["tmp_name"], $this->target_file)) {
            $this->error_message = "The file " . htmlspecialchars(basename($this->target_file)) . " has been uploaded.";
            return true;
        } else {
            $this->error_message = "Sorry, there was an error uploading your file.";
            return false;
        }
    }

    public function GetImgPath()
    {
        if ($this->upload_success) {
            // Construct the relative path
            $relative_path = str_replace('../', '', $this->target_file);
            return self::$rootFolderName . $relative_path;
        } else {
            return null;
        }
    }

    // Method to check if the upload was successful
    public function isUploadSuccessful()
    {
        return $this->upload_success;
    }

    // Method to get the error message
    public function getErrorMessage()
    {
        return $this->error_message;
    }
}
