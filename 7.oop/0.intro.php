<?php
// It's not normally necessary to manually clean up object (using __destruct())
// But if you have a large loop, unset() can help keep memory usage from out of control

// You should not reference another object inside __destruct(), because it may
// already been destroyed.

// You can use interfaces and traits together, this is actually best-practice design

// Cons of magical accessors (__get(), __set(), __unset(), __isset()):
// 1. relatively slow than direct property access and explicite accessor methods
// 2. make hard for Reflectioin classes and tools such as phpDocumentor
// 3. cannot use them with static properties

// Fluent Interface: the key is to return $this within every chainable method
// Very elegant, but try not to overuse them
// Best when tied to domains with a well-defined language such as SQL

// You can't instantiate object with inplace string concatenation like
//      new $prefix.$className;
// the solution is, preconcatenate class name
//      $class = $refix.$className;
//      new $class;

// Introspection
// ===============================================================
// is useful for
// - creating automated class documentation
// - generic object debugger
// - and state savers, like serialize()

class Person {
    public $name;
}

// ::export - quick overview of class
// This let you extract code from a file and place in your documentation
Reflection::export(new ReflectionClass('Person'));
