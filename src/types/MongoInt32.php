<?hh

/**
* The class can be used to save 32-bit integers to the database on a 64-bit
* system.
*/
class MongoInt32
{
	public $value;

	/**
	* Creates a new 32-bit integer.
	*
	* @param string $value - A number.
	*
	* @return - Returns a new integer.
	*/
	public function __construct(string $value)
	{
		$this->value = $value;
	}

	/**
	* Returns the string representation of this 32-bit integer.
	*
	* @return string - Returns the string representation of this integer.
	*/
	public function __toString()
	{
		return $this->value;
	}
}