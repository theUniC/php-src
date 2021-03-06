--TEST--
Bug #78272: calling preg_match() before pcntl_fork() will freeze child process
--SKIPIF--
<?php
if (!extension_loaded('pcntl')) die("skip pcntl extension required");
?>
--FILE--
<?php
preg_match('/abc/', 'abcde', $r);

$pid = pcntl_fork();
if ($pid === 0) {
    print "Child start\n";
    preg_match('/abc/', 'abcde', $r);
    print_r($r);
    print "End child\n";
    exit(0);
} else {
    print "Main start\n";
    pcntl_waitpid($pid, $status);
    print "End Main\n";
    exit(0);
}
?>
--EXPECT--
Main start
Child start
Array
(
    [0] => abc
)
End child
End Main
