<?php
// loopg through a list of files, include them, then gather function and method information
// about them, sort and print out

ob_start();
if ($argc < 2) {
    print "$argv[0]: classes1.php [, ...]\n";
    exit;
}

foreach (array_slice($argv, 1) as $filename) {
    include_once $filename;
}
$blackhole = ob_end_clean(); // use ob to eliminate included file's output

$methods = [];
foreach (get_declared_classes() as $class) {
    $r = new ReflectionClass($class);
    // eliminate built-in class
    if ($r->isUserDefined()) {
        foreach ($r->getMethods() as $method) {
            // eliminate inherited methods
            if ($method->getDeclaringClass()->getName() == $class) {
                $signature = "$class::" . $method->getName();
                $methods[$signature] = $method;
            }
        }
    }
}

$functions = [];
$defined_functions = get_defined_functions();
foreach ($defined_functions['user'] as $function) {
    $functions[$function] = new ReflectionFunction($function);
}

// sort methods
function sort_methods($a, $b) {
    list($a_class, $a_method) = explode('::', $a);
    list($b_class, $b_method) = explode('::', $b);
    if ($cmp = strcasecmp($a_class, $b_class)) {
        return $cmp;
    }
    return strcasecmp($a_method, $b_method);
}
uksort($methods, 'sort_methods');

// sort functions
unset($functions['sort_methods']);
ksort($functions);

foreach (array_merge($functions, $methods) as $name => $reflect) {
    $file = $reflect->getFileName();
    $line = $reflect->getStartLine();
    printf("%-25s | %-40s | %6d\n", "$name()", $file, $line);
}

/**
 * Test
 * ===============================================================
 * php 4.whereis.php 1.clone.php
 * Address::getcity()        | /Users/fanghao/Projects/learn/phpckbk/7.oop/1.clone.php |      9
 * Address::setCity()        | /Users/fanghao/Projects/learn/phpckbk/7.oop/1.clone.php |      8
 * Person::__call()          | /Users/fanghao/Projects/learn/phpckbk/7.oop/1.clone.php |     19
 * Person::__construct()     | /Users/fanghao/Projects/learn/phpckbk/7.oop/1.clone.php |     16
 * Person::getName()         | /Users/fanghao/Projects/learn/phpckbk/7.oop/1.clone.php |     18
 * Person::setName()         | /Users/fanghao/Projects/learn/phpckbk/7.oop/1.clone.php |     17
 * Person2::__clone()        | /Users/fanghao/Projects/learn/phpckbk/7.oop/1.clone.php |     41
 */
