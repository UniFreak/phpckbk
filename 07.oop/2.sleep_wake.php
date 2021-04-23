<?php
class LogFile {
    protected $filename;
    protected $handle;

    public function __construct($filename) {
        $this->filename = $filename;
        $this->open();
    }

    private function open() {
        $this->handle = fopen($this->filename, 'a');
    }

    public function __destruct() {
        fclose($this->handle);
    }

    // called when object is serialized
    // should return array of object prop to serialize
    // NOTE: same instance can be serialized multiple times in a single request,
    // or even continue to be used after it's serialized. So you shouldn't do
    // anything that could prevent either of these two actions
    public function __sleep() {
        return ['filename'];
    }

    // called when object is unserialized
    public function __wakeUp() {
        $this->open();
    }
}

$log = new LogFile('./log');
$ser = serialize($log);
print_r(unserialize($ser));