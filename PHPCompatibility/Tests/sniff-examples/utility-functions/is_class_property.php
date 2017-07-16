<?php

/* Case 1 */
$var = false;

/* Case 2 */
function something($var = false)
{
	/* Case 3 */
	$var = false;
}

class MyClass {
	/* Case 4 */
	public $var = true;
	/* Case 5 */
	protected $var = true;
	/* Case 6 */
	private $var = true;
	/* Case 7 */
	var $var = true;

	/* Case 8 */
	public function something($var = false)
	{
		/* Case 9 */
		$var = false;
	}
}

$a = new class {
	/* Case 10 */
	public $var = true;
	/* Case 11 */
	protected $var = true;
	/* Case 12 */
	private $var = true;
	/* Case 13 */
	var $var = true;

	/* Case 14 */
	public function something($var = false)
	{
		/* Case 15 */
		$var = false;
	}
}

interface MyInterface {
	// Properties are not allowed in interfaces.
	/* Case 16 */
	public $var = false;
	/* Case 17 */
	protected $var = false;
	/* Case 18 */
	private $var = false;
	/* Case 19 */
	var $var = false;

	/* Case 20 */
	public function something($var = false);
}

trait MyTrait {
	/* Case 22 */
	public $var = true;
	/* Case 23 */
	protected $var = true;
	/* Case 24 */
	private $var = true;
	/* Case 25 */
	var $var = true;

	/* Case 26 */
	function something($var = false)
	{
		/* Case 27 */
		$var = false;
	}
}
