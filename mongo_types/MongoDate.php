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
class MongoDate {

  /* Fields */
  public int $sec;
  public int $usec;

  private int $msec;
  /**
   * Creates a new date.
   *
   * @param int $sec - sec    Number of seconds since January 1st, 1970.
   * @param int $usec - usec    Microseconds. Please be aware though that
   *   MongoDB's resolution is milliseconds and not microseconds, which
   *   means this value will be truncated to millisecond resolution.
   *
   * @return  - Returns this new date.
   */
  public function __construct(int $sec = 0,
                              int $usec = 0): void {
    if ($sec == 0) {
      $this->sec = time();
    } else {
      $this->sec = $sec;
    }
    
    $this->usec = ((int) ($usec/1000))*1000;
    $this->msec = 1000*($this->sec) + 1000*($this->usec);
  }

  /**
   * Returns a string representation of this date
   *
   * @return string - This date.
   */
  public function __toString(): string {
    $seconds = (int) ($this->msec/1000);
    $milliseconds = ($this->msec) - $seconds*1000;

    return sprintf("%d %d", $milliseconds, $seconds);
  }
}
