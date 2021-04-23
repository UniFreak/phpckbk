<?php if ($_SERVER['REQUEST_METHOD'] == 'GET') { ?>

<form method="POST" action="<?php echo htmlentities($_SERVER['SCRIPT_NAME']) ?>" enctype="multipart/form-data">
    <input type="file" name="document"/>
    <input type="submit" value="SEND"/>
</form>

<?php } else {
    /**
     * $_FILE contains
     * - name: file name
     * - tyep: MIME type
     * - size
     * - tmp_name: location temporarily stored
     * - error: error code
     *     + UPLOAD_ERR_OK 0
     *     +        ..._INI_SIZE 1: bigger than upload_max_filesize
     *     +        ..._FORM_SIZE 2: bigger than form's MAX_FILE_SIZE
     *     +        ..._PARTIAL 3: only part was uploaded
     *     +        ..._NO_FILE 4
     *     +        ..._NO_TMP_DIR 6: no temp dir to store
     *     +        ..._CANT_WRITE 7: can't write to disk
     *     +        ..._EXTENSION 8: upload stopped by a PHP extension
     */
    if (isset($_FILES['document']) && ($_FILES['document']['error'] == UPLOAD_ERR_OK)) {
        $newPath = '/tmp/' . basename($_FILES['document']['name']);
        // move_uploaded_file() will check the file is really an uploaded file
        // just like is_uploaded_file()
        if (move_uploaded_file($_FILES['document']['tmp_name'], $newPath)) {
            echo "saved to $newPath";
        } else {
            echo "Can't move file to $newPath";
        }
    } else {
        echo "No valid file uploaded";
    }
} ?>