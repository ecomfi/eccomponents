<?php

require_once '../utils/EcIterableContainer.class.php';

class TestEcIterableContainer extends EcIterableContainer
{
	public function __construct($data, $first=0)
	{
		$this->data = $data;
		$this->first = $first;
	}
}


$data = array('data1','data2', 'data3', 'data4');
$it = new TestEcIterableContainer($data);

echo "ArrayAccess tests:\n";
var_dump(assert(isset($it[0])));
var_dump(assert($it[0] == 'data1'));
var_dump(assert(isset($it[3])));
var_dump(assert($it[3] == 'data4'));
var_dump(assert(!isset($it[4])));
var_dump(assert($it[4] === null));

echo "Iterator tests:\n";

$it->rewind();
var_dump(assert(!$it->hasPrevious()));
var_dump(assert($it->hasNext()));
var_dump(assert($it->count() == 4));
var_dump(assert($it->current() == 'data1'));
var_dump(assert($it->getNext() == 'data2'));
var_dump(assert($it->current() == 'data1'));

$it->seek(3);
var_dump(assert($it->hasPrevious()));
var_dump(assert(!$it->hasNext()));
var_dump(assert($it->current() == 'data4'));

try {
	$it->seek(-1);
	assert(false);
}
catch (OutOfBoundsException $e) { }

try {
	$it->seek(4);
	assert(false);
}
catch (OutOfBoundsException $e) { }


$data = array(10=>'data1',11=>'data2', 12=>'data3', 13=>'data4');
$it = new TestEcIterableContainer($data, 10);

echo "ArrayAccess tests 2:\n";

var_dump(assert(isset($it[10])));
var_dump(assert($it[10] == 'data1'));
var_dump(assert(isset($it[13])));
var_dump(assert($it[13] == 'data4'));
var_dump(assert(!isset($it[4])));
var_dump(assert($it[4] === null));

echo "Iterator tests 2:\n";

$it->rewind();
var_dump(assert(!$it->hasPrevious()));
var_dump(assert($it->hasNext()));
var_dump(assert($it->count() == 4));
var_dump(assert($it->current() == 'data1'));
var_dump(assert($it->getNext() == 'data2'));
var_dump(assert($it->current() == 'data1'));

$it->seek(13);
var_dump(assert($it->hasPrevious()));
var_dump(assert(!$it->hasNext()));
var_dump(assert($it->current() == 'data4'));

try {
	$it->seek(9);
	assert(false);
}
catch (OutOfBoundsException $e) { }

try {
	$it->seek(14);
	assert(false);
}
catch (OutOfBoundsException $e) { }

?>