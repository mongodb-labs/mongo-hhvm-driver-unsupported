<?hh

/**
 * A cursor is used to iterate through the results of a database query.
 * A cursor does not "contain" the database results, it just manages them.
 */
class MongoCursor implements \Iterator {

  /* variables */
  private $at = 0;
  private $batchSize = 100;
  private $connection = null;
  private $dead = false;
  private $wait = true;
  private $fields = [];
  private $flags = [];
  private $immortal = false;
  private $isSpecial = false;
  private $limit = 0;
  private $ns = null;
  private $partialResultsOK = false;
  private $query = [];
  private $timeout = 100;
  private $read_preference = [];
  private $skip = 0;
  private $slaveOkay = false;
  private $started_iterating = false;
  private $tailable = false;

  // NATIVE FUNCTIONS
  /**
   * Returns the current element
   *
   * @return array - The current result as an associative array.
   */
  <<__Native>>
  public function current(): array;

  /**
   * Checks if there are any more elements in this cursor.
   * May be hard to do in both php and c++
   *
   * @return bool - Returns if there is another element.
   */
  <<__Native>>
  public function hasNext(): bool;

  /**
   * Advances the cursor to the next result
   *
   * @return void - NULL.
   */
  <<__Native>>
  public function next(): void;

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
   * Checks if the cursor is reading a valid result.
   *
   * @return bool - If the current result is not null.
   */
  <<__Native>>
  public function valid(): bool;



  //NON-NATIVE FUNCTIONS

  /**
   * Adds a top-level key/value pair to a query
   *
   * @param string $key - key    Fieldname to add.
   * @param mixed $value - value    Value to add.
   *
   * @return MongoCursor - Returns this cursor.
   */
  public function addOption(string $key,
                            mixed $value): MongoCursor {
    if ($this->started_iterating) {
      throw new MongoCursorException("Tried to add an option after started iterating");
    }

    // Make the query object special (i.e. wrap in $query) if necessary
    if ( ! $this->isSpecial) {
      $this->query['$query'] = $this->query;
      $this->isSpecial = true;
    }

    $this->query[$key] = $value;
    return $this;
  }

  /**
   * Sets whether this cursor will wait for a while for a tailable cursor to
   * return more data
   *
   * @param bool $wait - wait    If the cursor should wait for more data
   *   to become available.
   *
   * @return MongoCursor - Returns this cursor.
   */
  public function awaitData(bool $wait = true): MongoCursor {
    $this->wait = $wait;
    return $this;
  }

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
  public function batchSize(int $batchSize): MongoCursor {
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

  public function __construct(MongoClient $connection,
                              string $ns,
                              array $query = array(),
                              array $fields = array()) {
    $this->connection = $connection;
    $this->ns = $ns;
    $this->query = $query;
    $this->fields = $fields;

  }

  /**
   * Counts the number of results for this query
   *
   * @param bool $foundOnly -
   *
   * @return int - The number of documents returned by this cursor's
   *   query.
   */
  public function count(bool $foundOnly = false): int {
    $pieces = explode($this->ns);
    $db_name = $pieces[0];
    $collection_name = $pieces[1];

    $db = $this->connection->selectDB($db_name);
    $query = ["count" => $collection_name];
    if ($foundOnly) {
      if ($this->limit > 0) {
        $query["limit"] = $this->limit;
      }
      if ($this->skip > 0) {
        $query["skip"] = $this->skip;
      }
    } 

    $command_result = $db->command($query);
    if (!$command_result["ok"]) {
      throw new MongoCursorException();
    }
    return $command_result["n"];
  }

  /**
   * Checks if there are documents that have not been sent yet from the
   * database for this cursor
   *
   * @return bool - Returns if there are more results that have not been
   *   sent to the client, yet.
   */
  public function dead(): bool {
    return $this->dead;
  }
 
  /**
   * Return an explanation of the query, often useful for optimization and
   * debugging
   *
   * @return array - Returns an explanation of the query.
   */
  public function explain(): array {
    $this->reset();
    $temp_limit = $this->limit;
    $this->limit = -1;
    $this->query['$explain'] = true;
    $this->rewind(); //TODO: Remove when gh50 branch merged
    $ret = $this->current();
    //var_dump($ret);
    $this->reset();
    $this->limit = $temp_limit;
    unset($this->query['$explain']);
    //var_dump($this);

    // $temp_limit = $this->limit;
    // $this->limit = -1;
    // $this->query['$explain'] = true;
    // $this->rewind();
    // $ret = $this->current();
    // $this->limit = $temp_limit;
    // unset($this->query['$explain']);
    // $this->rewind();
    return $ret;
  }

  /**
   * Sets the fields for a query
   *
   * @param array $f - f    Fields to return (or not return).
   *
   * @return MongoCursor - Returns this cursor.
   */
  public function fields(array $fields) {
    if ($this->started_iterating) {
      throw new MongoCursorException("Tried to change fields after started iterating");
    }
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
      $this->next();
      return $this->current();
  }

  /**
   * Get the read preference for this query
   *
   * @return array -
   */
  public function getReadPreference(): array {
    return $this->read_preference;
  }

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
  public function hint(mixed $index): MongoCursor {

    if (is_object($index)) {
      $index = get_object_vars($index);
    }

    if (is_array($index)) {
      $index = MongoCollection::_toIndexString($index);
    }

    $this->addOption('$hint', $index);
    return $this;
  }

  /**
   * Sets whether this cursor will timeout
   *
   * @param bool $liveForever - liveForever    If the cursor should be
   *   immortal.
   *
   * @return MongoCursor - Returns this cursor.
   */
  public function immortal(bool $liveForever = true): MongoCursor {
    if ($this->started_iterating) {
      throw new MongoCursorException("Tried to add an option after started iterating");
    }

    $this->immortal = $liveForever;
    return $this;
  }

  /**
   * Gets the query, fields, limit, and skip for this cursor
   *
   * @return array - Returns the namespace, limit, skip, query, and
   *   fields for this cursor.
   */
  public function info(): array {
    $info = [
      "ns" => $this->ns,
      "limit" => $this->limit,
      "batchSize" => $this->batchSize,
      "skip" => $this->skip,
      "flags" => $this->flags,
      "query" => $this->query,
      "fields" => $this->fields
    ];
    return $info;
  }

  /**
   * Returns the current results _id
   *
   * @return string - The current results _id as a string.
   */
  public function key(): mixed {
    $current = $this->current();

    if ($current === null) {
      return null;
    }

    if (is_array($current) && array_key_exists('_id', $current)) {
      return (string) $current['_id'];
    }

    if (is_object($current) && property_exists($current, '_id')) {
      return (string) $current->_id;
    }

    return $this->at - 1;
  }
  /**
   * Limits the number of results returned
   *
   * @param int $num - num    The number of results to return.
   *
   * @return MongoCursor - Returns this cursor.
   */
  public function limit(int $num) {
    if ($this->started_iterating) {
      throw new MongoCursorException("Tried to add an option after started iterating");
    }
    $this->limit = $num;
    return $this;
  }

  /*
   * If this query should fetch partial results from  if a shard is down
   *
   * @param bool $okay - okay    If receiving partial results is okay.
   *
   * @return MongoCursor - Returns this cursor.
   */
  public function partial(bool $okay = true): MongoCursor {
    if ($this->started_iterating) {
      throw new MongoCursorException("Tried to add an option after started iterating");
    }
    $this->partialResultsOK = $okay;
    return $this;
  }

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
  public function setFlag(int $flag,
                          bool $set = true): MongoCursor {
    if ($this->started_iterating) {
      throw new MongoCursorException("Tried to add an option after started iterating");
    }
    $this->flags[$flag] = $set;
    return $this;
  }

  /**
   * Set the read preference for this query
   *
   * @param string $read_preference -
   * @param array $tags -
   *
   * @return MongoCursor - Returns this cursor.
   */
  public function setReadPreference(string $read_preference,
                                    array $tags): MongoCursor {
    if ($this->started_iterating) {
      throw new MongoCursorException("Tried to add an option after started iterating");
    }
    $this->read_preference['type'] = $read_preference;
    $this->read_preference['tagsets'] = $tags;
    return $this;
  }

  /**
   * Skips a number of results
   *
   * @param int $num - num    The number of results to skip.
   *
   * @return MongoCursor - Returns this cursor.
   */
  public function skip(int $num): MongoCursor {
    if ($this->started_iterating) {
      throw new MongoCursorException("Tried to add an option after started iterating");
    }
    $this->skip = $num;
    return $this;
  }

  /** DEPRECATED
   * Sets whether this query can be done on a secondary
   *
   * @param bool $okay - okay    If it is okay to query the secondary.
   *
   * @return MongoCursor - Returns this cursor.
   */
  public function slaveOkay(bool $okay = true): MongoCursor {
    if ($this->started_iterating) {
      throw new MongoCursorException("Tried to add an option after started iterating");
    }
    $this->slaveOkay = $okay;
    return $this;
  }

  /**
   * Use snapshot mode for the query
   *
   * @return MongoCursor - Returns this cursor.
   */
  public function snapshot() {
    $this->addOption('$snapshot', true);
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
    $this->addOption('$orderby', $fields);
    return $this;
  }

  /** TODO: Make sure C++ code closes cursor appropriately
   * Sets whether this cursor will be left open after fetching the last
   * results
   *
   * @param bool $tail - tail    If the cursor should be tailable.
   *
   * @return MongoCursor - Returns this cursor.
   */
  public function tailable(bool $tail = true): MongoCursor {
    if ($this->started_iterating) {
      throw new MongoCursorException("Tried to add an option after started iterating");
    }
    $this->tailable = $tail;
    return $this;
  }

  /** TODO: How does query-side timeout work?
   * Sets a client-side timeout for this query
   *
   * @param int $ms -
   *
   * @return MongoCursor - This cursor.
   */
  public function timeout(int $ms): MongoCursor {
    if ($this->started_iterating) {
      throw new MongoCursorException("Tried to add an option after started iterating");
    }
    $this->timeout = $ms;
    return $this;
  }

}
