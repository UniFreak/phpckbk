<?php
// Using reflection to locate function and method definitions

class Dummy {
    public function dummy2() {
        return 'dummy';
    }
}

if ($argc < 2) {
    print "$argv[0]: function/method, classes1.php [, ...classesN.php]\n";
    exit;
}

$function = $argv[1];
foreach (array_slice($argv, 2) as $filename) {
    include_once $filename;
}

try {
    if (strpos($function, '::')) {
        // It's a method
        list($class, $method) = explode('::', $function);
        $reflect = new ReflectionMethod($class, $method);
    } else {
        // It's a function
        $reflect = new ReflectionFunction($function);
    }
    $file = $reflect->getFileName();
    $line = $reflect->getStartLine();

    printf("%s | %s | %d\n", "$function()", $file, $line);
} catch (ReflectioinException $e) {
    printf("%s not found.\n", "$function()");
}

// Test
// ===============================================================
//
// php ./3.introspect.php Dummy::dummy2 3.introspect.php
// Dummy::dummy2() | /Users/fanghao/Projects/learn/phpckbk/7.oop/3.introspect.php | 5