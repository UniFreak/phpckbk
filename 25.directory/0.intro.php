<?php
/**
 * File are orgnized with incodes.
 *
 * PHP use stat() internally to give you a specific info about file
 *
 * setuid: when a program is run, it runs with the user ID of its owner
 * setgid: run with group ID of its group
 * sticky bit: useful for directories in which people share files becuase
 *             it prevents nonsuperusers with write permission in a
 *             directory from deleting files in that directory
 *             unless they own the file or the directory
 */

// Iterate
// ===============================================================
// 1. DirectoryIterator
foreach (new DirectoryIterator('.') as $file) {
    // Object methods
    //      is(Dir/Dot/File/Link/Readable/Writable/Executable)
    //      get(A/C/M)Time get(File/Path)name
    //      get(Path/Group/Owner/Perms/Size/Type/Inode)
    echo $file->getPathname() . "\n";
}

// 2. readdir()
$d = opendir('.');
while (false !== ($f = readdir($d))) {
    echo $f . "\n";
}
closedir($d);

// Umask
// ===============================================================
// PHP run as a server module, it restore the umask to its default
// at the end of each request
$old_umask = umask(0077); // return old mask
touch('secret-file.txt');
umask($old_umask);

// Times
// ===============================================================
// 1. access time
printf("last access:%d\n", fileatime('secret-file.txt'));
// 2. modify time
printf("last modify:%d\n", filemtime('secret-file.txt'));
// 3. contents or metadata change
printf("last content, data change:%d\n", filectime('secret-file.txt'));

// 4. modify
touch('secret-file.txt');

// Stat
// ===============================================================
// expensive, becuase of stat(2) system call

// 1. filename
print_r(stat('secret-file.txt'));
// 2. link itself
print_r(lstat('secret-file.txt'));
// 3. file handler
print_r(fstat(fopen('secret-file.txt', 'r')));
// 4. clear cache
//    PHP use cache for functions like:
//      file_exists, file(a/c/m)time,
//      file(group/time/inode/owner/perms/size/type/stat)
//      is_(dir/executable/file/link/readable/writable)
//      lstat
clearstatcache();

// Change Perms
// ===============================================================
// chmod, chown, chgrp

// Filename
// ===============================================================
// using functions rather than splitting up is more portable
$name = '/usr/local/php/php.ini';
printf("basename: %s\n", basename($name));
printf("dirname: %s\n", dirname($name));
print_r(pathinfo($name));


// Delete
// ===============================================================
unlink('secret-file.txt');

// Copy & Move
// ===============================================================
copy('0.intro.php', 'copied');
rename('copied', 'moved');


// Filename Pattern
// ===============================================================
// 1. Filter
class PhpFilter extends FilterIterator {
    public function accept() {
        return preg_match('@\.php$@i', $this->current());
    }
}
foreach (new PhpFilter(new DirectoryIterator('..')) as $img) {
    echo "$img\n";
}

// 2. glob
foreach (glob('../*.php') as $file) {
    echo "$file\n";
}

// Process Recursively
// ===============================================================
$dir = new RecursiveDirectoryIterator('./');
$totalSize = 0;
// RecursiveIteratorIterator flattens the hierarchy that
// RecursiveDirectoryIterator retuned into one list
foreach (new RecursiveIteratorIterator($dir) as $file) {
    $totalSize += $file->getSize();
}
echo "size: $totalSize\n";

// Make New Dir
// ===============================================================
mkdir('./tmp', 0777);
clearstatcache();

// Remove Dir
// ===============================================================
function obliterate_directory($dir) {
    $iter = new RecursiveDirectoryIterator($dir);
    foreach (new RecursiveIteratorIterator($iter, RecursiveIteratorIterator::CHILD_FIRST) as $f) {
        if ($f->isDir()) {
            rmdir($f->getPathname());
        } else {
            unlink($f->getPathname());
        }
        rmdir($dir);
    }
}
obliterate_directory('./tmp');
