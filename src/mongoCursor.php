<?hh

/**
 * A cursor is used to iterate through the results of a database query.
 * A cursor does not "contain" the database results, it just manages them.
 */
class MongoCursor {

  /* variables */

  private $batchSize = 100;
  private $fields = [];
  private $query = [];
  private $queryLimit = 0;
  private $querySkip = 0;
  private $queryTimeout = null;
  private $started_iterating = false;
  private $tailable = false;

  /**
   * Adds a top-level key/value pair to a query
   *
   * @param string $key - key    Fieldname to add.
   * @param mixed $value - value    Value to add.
   *
   * @return MongoCursor - Returns this cursor.
   */
  <<__Native>>
  public function addOption(string $key,
                            mixed $value): object;

  /**
   * Sets whether this cursor will wait for a while for a tailable cursor to
   * return more data
   *
   * @param bool $wait - wait    If the cursor should wait for more data
   *   to become available.
   *
   * @return MongoCursor - Returns this cursor.
   */
  <<__Native>>
  public function awaitData(bool $wait = true): object;

  /**
   * Limits the number of elements returned in one batch.
   *
   * @param int $batchSize - batchSize    The number of results to return
   *   per batch. If batchSize is 2 or more, it represents the size of each batch of
   *   objects retrieved. If batchSize is 1 or negative, it will limit
   *   of number returned documents to the absolute value of batchSize, and
   *   the cursor will be closed. The batch size can be changed even
   *   after a cursor is iterated, in which case the setting will apply on
   *   the next batch retrieval.
   *
   * @return MongoCursor - Returns this cursor.
   */
  public function batchSize(int $batchSize) {
    //TODO: Handle non-positive batch size
    $this->batchSize = $batchSize;
    return $this;
  }

  /**
   * Create a new cursor
   *
   * @param mongoclient $connection - connection    Database connection.
   * @param string $ns - ns    Full name of database and collection.
   * @param array $query - query    Database query.
   * @param array $fields - fields    Fields to return.
   *
   * @return  - Returns the new cursor.
   */
  <<__Native>>
  public function __construct(MongoClient $connection,
                              string $ns,
                              array $query = array(),
                              array $fields = array()): void;

  /**
   * Counts the number of results for this query
   *
   * @param bool $foundOnly -
   *
   * @return int - The number of documents returned by this cursor's
   *   query.
   */
  <<__Native>>
  public function count(bool $foundOnly = false): int;

  /**
   * Returns the current element
   *
   * @return array - The current result as an associative array.
   */
  <<__Native>>
  public function current(): array;

  /**
   * Checks if there are documents that have not been sent yet from the
   * database for this cursor
   *
   * @return bool - Returns if there are more results that have not been
   *   sent to the client, yet.
   */
  <<__Native>>
  public function dead(): bool;

  /**
   * Execute the query.
   *
   * @return void - NULL.
   */
  <<__Native>>
  protected function doQuery(): void;

  /**
   * Return an explanation of the query, often useful for optimization and
   * debugging
   *
   * @return array - Returns an explanation of the query.
   */
  <<__Native>>
  public function explain(): array;

  /**
   * Sets the fields for a query
   *
   * @param array $f - f    Fields to return (or not return).
   *
   * @return MongoCursor - Returns this cursor.
   */
  public function fields(array $fields) {
    $this->fields = $fields;
    return $this;
  }

  /**
   * Return the next object to which this cursor points, and advance the
   * cursor
   *
   * @return array - Returns the next object.
   */
  public function getNext(): array {
    $current_record = $this->current();
    $this->next();

    return $record;
  }

  /**
   * Get the read preference for this query
   *
   * @return array -
   */
  <<__Native>>
  public function getReadPreference(): array;

  /**
   * Checks if there are any more elements in this cursor
   *
   * @return bool - Returns if there is another element.
   */
  <<__Native>>
  public function hasNext(): bool;

  /**
   * Gives the database a hint about the query
   *
   * @param mixed $index - index    Index to use for the query. If a
   *   string is passed, it should correspond to an index name. If an array
   *   or object is passed, it should correspond to the specification used
   *   to create the index (i.e. the first argument to
   *   MongoCollection::ensureIndex()).
   *
   * @return MongoCursor - Returns this cursor.
   */
  <<__Native>>
  public function hint(mixed $index): object;

  /**
   * Sets whether this cursor will timeout
   *
   * @param bool $liveForever - liveForever    If the cursor should be
   *   immortal.
   *
   * @return MongoCursor - Returns this cursor.
   */
  <<__Native>>
  public function immortal(bool $liveForever = true): object;

  /**
   * Gets the query, fields, limit, and skip for this cursor
   *
   * @return array - Returns the namespace, limit, skip, query, and
   *   fields for this cursor.
   */
  <<__Native>>
  public function info(): array;

  /**
   * Returns the current results _id
   *
   * @return string - The current results _id as a string.
   */
  <<__Native>>
  public function key(): string;

  /**
   * Limits the number of results returned
   *
   * @param int $num - num    The number of results to return.
   *
   * @return MongoCursor - Returns this cursor.
   */
  public function limit(int $num) {
    $this->queryLimit = $num;
    return $this;
  }

  /**
   * Advances the cursor to the next result
   *
   * @return void - NULL.
   */
  <<__Native>>
  public function next(): void;

  /*
   * If this query should fetch partial results from  if a shard is down
   *
   * @param bool $okay - okay    If receiving partial results is okay.
   *
   * @return MongoCursor - Returns this cursor.
   */
  <<__Native>>
  public function partial(bool $okay = true): object;

  /**
   * Clears the cursor
   *
   * @return void - NULL.
   */
  <<__Native>>
  public function reset(): void;

  /**
   * Returns the cursor to the beginning of the result set
   *
   * @return void - NULL.
   */
  <<__Native>>
  public function rewind(): void;

  /**
   * Sets arbitrary flags in case there is no method available the specific
   * flag
   *
   * @param int $flag - flag    Which flag to set. You can not set flag 6
   *   (EXHAUST) as the driver does not know how to handle them. You will
   *   get a warning if you try to use this flag. For available flags,
   *   please refer to the wire protocol documentation.
   * @param bool $set - set    Whether the flag should be set (TRUE) or
   *   unset (FALSE).
   *
   * @return MongoCursor - Returns this cursor.
   */
  <<__Native>>
  public function setFlag(int $flag,
                          bool $set = true): object;

  /**
   * Set the read preference for this query
   *
   * @param string $read_preference -
   * @param array $tags -
   *
   * @return MongoCursor - Returns this cursor.
   */
  <<__Native>>
  public function setReadPreference(string $read_preference,
                                    array $tags): object;

  /**
   * Skips a number of results
   *
   * @param int $num - num    The number of results to skip.
   *
   * @return MongoCursor - Returns this cursor.
   */
  public function skip(int $num) {
    $this->querySkip = $num;
    return $this;
  }

  /**
   * Sets whether this query can be done on a secondary
   *
   * @param bool $okay - okay    If it is okay to query the secondary.
   *
   * @return MongoCursor - Returns this cursor.
   */
  <<__Native>>
  public function slaveOkay(bool $okay = true): object;

  /**
   * Use snapshot mode for the query
   *
   * @return MongoCursor - Returns this cursor.
   */
  public function snapshot() {
    $this->query['$snapshot'] = true;
    return $this;
  }

  /**
   * Sorts the results by given fields
   *
   * @param array $fields - fields    An array of fields by which to
   *   sort. Each element in the array has as key the field name, and as
   *   value either 1 for ascending sort, or -1 for descending sort.   Each
   *   result is first sorted on the first field in the array, then (if it
   *   exists) on the second field in the array, etc. 
   *
   * @return MongoCursor - Returns the same cursor that this method was
   *   called on.
   */
  public function sort(array $fields) {
    $this->query['$orderby'] = $fields;
    return $this;
  }

  /**
   * Sets whether this cursor will be left open after fetching the last
   * results
   *
   * @param bool $tail - tail    If the cursor should be tailable.
   *
   * @return MongoCursor - Returns this cursor.
   */
  public function tailable(bool $tail = true) {
    $this->tailable = $tail;
    return $this;
  }

  /**
   * Sets a client-side timeout for this query
   *
   * @param int $ms -
   *
   * @return MongoCursor - This cursor.
   */
  public function timeout(int $ms) {
    $this->queryTimeout = $ms;
    return $this;
  }

  /**
   * Checks if the cursor is reading a valid result.
   *
   * @return bool - If the current result is not null.
   */
  <<__Native>>
  public function valid(): bool;

}