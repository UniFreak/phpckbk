<?php
// Stream Wrapper: handle the details of moving data back and forth between PHP
// and your custom location or your custom format.
//
// registered with a particular prefix. You use that prefix when passing a
// filename to fopen(), include() or any other PHP file-handling function to
// ensure that your wrapper is invoked.
//
// handy for nonfile data sources, can alos be used to preprocess file contents
// on their way into PHP.
//
//

// Templating
// ===============================================================

/**
 * Stream wrapper to convert markup of mostly PHP templates into PHP prior to include()
 */
class ViewStream {
    private $pos = 0;
    private $data;
    private $stat;

    public function stream_open($path, $mode, $options, &$opened_path) {
        // get the view script source
        $path = str_replace('view://', '', $path);
        $this->data = file_get_contents($path);

        // If reading the file failed, update our local stat store to reflect
        // the real stat of the file, then return on failure
        if ($this->data === false) {
            $this->stat = stat($path); // stat(): give info about file
            return false;
        }

        /**
         * Convert <?= ?> to long-form <?php echo ?>
         *
         * We could also convert <%= like the real T_OPEN_TAG_WITH_ECHO
         * but that's not necessary
         */
        if (! ini_get('short_open_tag')) {
            $find = '/\<\?\= (.*)? \?>/';
            $replace = "<?php echo \$1 ?>";
            $this->data = preg_replace($find, $replace, $this->data);
        }

        /**
         * Convert @$ to $this->
         */
        $this->data = str_replace('@$', '$this->', $this->data);

        /**
         * file_get_contents() won't update PHP's stat cache, so performing another
         * stat() on it will hit the filesystem again. Since the file has been
         * sucessfully read, avoid this and just fake the stat so include is
         * happy
         */
        $this->stat = ['mode' => 0100777, 'size' => strlen($this->data)];

        return true;
    }

    public function stream_read($count) {
        $ret = substr($this->data, $this->pos, $count);
        $this->pos += strlen($ret);
        return $ret;
    }

    public function stream_tell()
    {
        return $this->pos;
    }

    public function stream_eof() {
        return $this->pos >= strlen($this->data);
    }

    public function stream_stat() {
        return $this->stat;
    }

    public function stream_seek($offset, $whence) {
        switch ($whence) {
            case SEEK_SET: // Set position equal to offset bytes
                if ($offset < strlen($this->data) && $offset >= 0) {
                    $this->pos = $offset;
                    return true;
                } else {
                    return false;
                }
                break;
            case SEEK_CUR: // Set position to current location plus offset
                if ($offset >= 0) {
                    $this->pos += $offset;
                    return true;
                } else {
                    return false;
                }
                break;
            case SEEK_END: // Set position to end-of-file plus offset
                if (strlen($this->data) + $offset >= 0) {
                    $this->pos = strlen($this->data) + $offset;
                    return true;
                } else {
                    return false;
                }
                break;
            default:
                return false;
        }
    }
}