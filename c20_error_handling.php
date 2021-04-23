<?php

function a() {
    b('some');
}

function b($any) {
    (new Cls)->c();
}

class Cls {
    function c() {
        print_r(debug_backtrace());
    }
}

a();