<?hh

/**
 * Represent date objects for the database. This class should be used to save
 * dates to the database and to query for dates. For example:   Storing dates
 * with MongoDate      MongoDB stores dates as milliseconds past the epoch.
 * This means that dates do not contain timezone information. Timezones must
 * be stored in a separate field if needed. Second, this means that any
 * precision beyond milliseconds will be lost when the document is sent
 * to/from the database.
 */

class MongoDate
{
	public $sec;
	public $usec;

	/**
	* Creates a new date.
	*
	* @param int $sec - Number of seconds since January 1st, 1970.
	* @param int $usec - Microseconds. Please be aware though that
	* MongoDB's resolution is milliseconds and not microseconds, which
	* means this value will be truncated to millisecond resolution.
	*
	* @return - Returns this new date.
	*/
	public function __construct($sec = -1, $usec = 0) {
		if ($sec < 0) {
				$this->sec = time();
		} else {
				$this->sec = $sec;
		}

		$this->usec = $usec;
	}

	/**
	* Returns a string representation of this date
	*
	* @return string - This date.
	*/
	public function __toString() {
		return (string) $this->sec . ' ' . $this->usec;
	}
}