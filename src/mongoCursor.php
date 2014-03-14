<?hh

/**
 * A cursor is used to iterate through the results of a database query. For
 * example, to query the database and see all results, you could do:
 * MongoCursor basic usage       You don't generally create cursors using the
 * MongoCursor constructor, you get a new cursor by calling
 * MongoCollection::find() (as shown above).   Suppose that, in the example
 * above, $collection was a 50GB collection. We certainly wouldn't want to
 * load that into memory all at once, which is what a cursor is for: allowing
 * the client to access the collection in dribs and drabs.   If we have a
 * large result set, we can iterate through it, loading a few megabytes of
 * results into memory at a time. For example, we could do:  Iterating over
 * MongoCursor     This will go through each document in the collection,
 * loading and garbage collecting documents as needed.   Note that this means
 * that a cursor does not "contain" the database results, it just manages
 * them. Thus, if you print a cursor (with, say, var_dump() or print_r()),
 * you'll just get the cursor object, not your documents. To get the documents
 * themselves, you can use one of the methods shown above.
 */
class MongoCursor {
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
   *   per batch. Each batch requires a round-trip to the server.   If
   *   batchSize is 2 or more, it represents the size of each batch of
   *   objects retrieved. It can be adjusted to optimize performance and
   *   limit data transfer.   If batchSize is 1 or negative, it will limit
   *   of number returned documents to the absolute value of batchSize, and
   *   the cursor will be closed. For example if batchSize is -10, then the
   *   server will return a maximum of 10 documents and as many as can fit
   *   in 4MB, then close the cursor.    A batchSize of 1 is special, and
   *   means the same as -1, i.e. a value of 1 makes the cursor only
   *   capable of returning one document.    Note that this feature is
   *   different from MongoCursor::limit() in that documents must fit
   *   within a maximum size, and it removes the need to send a request to
   *   close the cursor server-side. The batch size can be changed even
   *   after a cursor is iterated, in which case the setting will apply on
   *   the next batch retrieval.   This cannot override MongoDB's limit on
   *   the amount of data it will return to the client (i.e., if you set
   *   batch size to 1,000,000,000, MongoDB will still only return 4-16MB
   *   of results per batch).   To ensure consistent behavior, the rules of
   *   MongoCursor::batchSize() and MongoCursor::limit() behave a little
   *   complex but work "as expected". The rules are: hard limits override
   *   soft limits with preference given to MongoCursor::limit() over
   *   MongoCursor::batchSize(). After that, whichever is set and lower
   *   than the other will take precedence. See below. section for some
   *   examples.
   *
   * @return MongoCursor - Returns this cursor.
   */
  <<__Native>>
  public function batchSize(int $batchSize): object;

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
  public function __construct(MongoClient $connection,
                              string $ns,
                              array $query = array(),
                              array $fields = array()) {
    
    printf("Constructing MongoCursor\n");
  }

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
  <<__Native>>
  public function fields(array $f): object;

  /**
   * Return the next object to which this cursor points, and advance the
   * cursor
   *
   * @return array - Returns the next object.
   */
  <<__Native>>
  public function getNext(): array;

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
  <<__Native>>
  public function limit(int $num): object;

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
  <<__Native>>
  public function skip(int $num): object;

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
  <<__Native>>
  public function snapshot(): object;

  /**
   * Sorts the results by given fields
   *
   * @param array $fields - fields    An array of fields by which to
   *   sort. Each element in the array has as key the field name, and as
   *   value either 1 for ascending sort, or -1 for descending sort.   Each
   *   result is first sorted on the first field in the array, then (if it
   *   exists) on the second field in the array, etc. This means that the
   *   order of the fields in the fields array is important. See also the
   *   examples section.
   *
   * @return MongoCursor - Returns the same cursor that this method was
   *   called on.
   */
  <<__Native>>
  public function sort(array $fields): object;

  /**
   * Sets whether this cursor will be left open after fetching the last
   * results
   *
   * @param bool $tail - tail    If the cursor should be tailable.
   *
   * @return MongoCursor - Returns this cursor.
   */
  <<__Native>>
  public function tailable(bool $tail = true): object;

  /**
   * Sets a client-side timeout for this query
   *
   * @param int $ms -
   *
   * @return MongoCursor - This cursor.
   */
  <<__Native>>
  public function timeout(int $ms): object;

  /**
   * Checks if the cursor is reading a valid result.
   *
   * @return bool - If the current result is not null.
   */
  <<__Native>>
  public function valid(): bool;

}