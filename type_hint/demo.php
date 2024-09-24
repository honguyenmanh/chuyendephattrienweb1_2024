<?php
declare(strict_types=1);

require_once 'A.php';
require_once 'B.php';
require_once 'C.php'; 

class Demo {
    public function __construct() {
        echo "Demo class created.<br>";
    }
}

function typeXReturnY($x) {
    echo __FUNCTION__ . "<br>";
    return new C(); 
}

$a = new A();
$b = new B();

$a->a1(); 
echo "<br>";
$b->b1(); 
echo "<br>";

$demo = new Demo();  
$result = typeXReturnY($a); 

echo "TypeXReturnY function returned an object of class: " . get_class($result) . "<br>";
?>
