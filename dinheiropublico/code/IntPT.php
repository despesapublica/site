<?php
/**
 * Represents a signed 32 bit integer field.
 * 
 * @package sapphire
 * @subpackage model
 */
class IntPT extends Int {

	/**
	 * Returns the number, with commas added as appropriate, eg “1,000”.
	 */
	function Formatted() {
		return number_format($this->value, 0, '.', ' ');
	}

	function Nice() {
		return number_format($this->value, 0, '.', ' ');
	}
}

?>