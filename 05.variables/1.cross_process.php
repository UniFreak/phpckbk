<?php
// APCu
// ===============================================================
// APCu is the official replacement for the outdated APC extension. APC
// provided both opcode caching (opcache) and object caching. As PHP versions
// 5.5 and above include their own opcache, APC was no longer compatible, and
// its opcache functionality became useless. The developers of APC then created
// APCu, which offers only the object caching (read "in memory data caching")
// functionality (they removed the outdated opcache).
//
// Need to be install & enabled with PECL
// see <https://pecl.php.net/package/APCu>

// inc() & dec() useful for speedy counter
// add() can be used to implement lightweight locking
function update_recent_users($current_user) {
    $recent_users = apcu_fetch('recent-users', $success);
    if ($success) {
        if (! in_array($current_user, $recent_users)) {
            array_unshift($recent_users, $current_user);
        }
    } else {
        $recent_users = [$current_user];
    }
    $recent_users = array_slice($recent_users, 0, 10);
    apcu_store('recent-users', $recent_users);
}

$tries = 3;
$done = false;
while ((! $done) && ($tries-- > 0)) {
    if (apcu_add('my-lock', true, 5)) { // cache my-lock=true, ttl=5. only when not alreay stored
        update_recent_users('somebody');
        apcu_delete('my-lock');
        $done = true;
    }
}

print_r(apcu_fetch('recent-users'));

// shmop
// ===============================================================
// shared memory segment: a slice of machiines' RAM that difference process can access
// shmop segments are differentiated by keys
// keys are integers, not easy to remember, so best to use ftok()
//
// mode
// - a: open for read-only
// - c: create, open if already exists
// - w: open for read & write
// - n: create only when not eixsts. useful to avoid race condition
$shmop_key = ftok(__FILE__, 'p'); // convert filename to integer, project=p
$shmop_id = shmop_open($shmop_key, "c", 0600, 16384); // mode=c, perms=0600, size=16384
$population = (int) shmop_read($shmop_id, 0, 0); // offset=size=0
if (! $population) {
    $population = 0;
}
$population += 1;
$shmop_bytes_written = shmop_write($shmop_id, $population, 0); // offset=0
if ($shmop_bytes_written != strlen($population)) {
    echo "Can't write all of: $population\n";
}
printf("stored population: %d\n", shmop_read($shmop_id, 0, 0));
shmop_close($shmop_id);

// System V shared memory
// ===============================================================
// Can't use under Windows
// behave similar to array
// requires you do locking with semaphore:
//      process needs to get control of semaphor before use memory segment
//      hence don't step on each other's toes
//      semaphore key can by any integer

$semaphore_id = 100;
$segment_id = 200;
// get a handle to the semaphore associated with the shared memory segment we want
$sem = sem_get($semaphore_id, 1, 0600); // return semaphore id
// ensure exclusive access to the semaphore
sem_acquire($sem) or die("Can't acquire semaphore"); // return pointer to System Semphore

// get a handle to shared memory segment
$shm = shm_attach($segment_id, 16384, 0600); // return shared memory segment (shm) id
// each value stored in segment is identified by an integer ID
// you can store any type of variable in shared memory
$var_id = 3476;
if (shm_has_var($shm, $var_id)) {
    $population = shm_get_var($shm, $var_id);
} else {
    $population = 0;
}
$population += 1;
shm_put_var($shm, $var_id, $population);

printf("population after shmop: %d\n", shm_get_var($shm, $var_id));
shm_detach($shm);
sem_release($sem);