--TEST--
Bug #79096 (FFI Struct Segfault)
--SKIPIF--
<?php
if (!extension_loaded('ffi')) die('skip ffi extension not available');
if (!extension_loaded('zend-test')) die('skip zend-test extension not available');
?>
--FILE--
<?php
$header = <<<HEADER
struct bug79096 {
    uint64_t a;
    uint64_t b;
};

struct bug79096 bug79096(void);
HEADER;

if (PHP_OS_FAMILY !== 'Windows') {
    $ffi = FFI::cdef($header);
} else {
    try {
        $ffi = FFI::cdef($header, 'php_zend_test.dll');
    } catch (FFI\Exception $ex) {
        $dll = $dll = 'php7' . (PHP_ZTS ? 'ts' : '') . (PHP_DEBUG ? '_debug' : '') . '.dll';
        $ffi = FFI::cdef($header, $dll);
    }
}

$struct = $ffi->bug79096();
var_dump($struct);
?>
--EXPECTF--
object(FFI\CData:struct bug79096)#%d (2) {
  ["a"]=>
  int(1)
  ["b"]=>
  int(1)
}
