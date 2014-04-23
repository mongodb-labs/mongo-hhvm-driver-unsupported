<?hh

/**
 * Represents a MongoDB collection.   Collection names can use any character
 * in the ASCII set. 
 */
class MongoCollection {

  /* Constants */
  const ASCENDING = 1 ;
  const DESCENDING = -1 ;
  
  /* Fields */
  private $db = NULL;
  private $w;
  private $wtimeout;

  /* Variables */
  private $name;
  private $slaveOkay = false;
  private $read_preference = [];


/**
   * Inserts a document into the collection
   *
   * @param array|object $a - a    An array or object. If an object is
   *   used, it may not have protected or private properties.    If the
   *   parameter does not have an _id key or property, a new MongoId
   *   instance will be created and assigned to it.
   * @param array $options - options    Options for the insert.
   *
   * @return bool|array - Returns an array containing the status of the
   *   insertion if the "w" option is set. Otherwise, returns TRUE if the
   *   inserted array is not empty (a MongoException will be thrown if the
   *   inserted array is empty). 
   */
  <<__Native>>
  public function insert(mixed $a,
                         array $options = array()): mixed;

  /**
   * Remove records from this collection
   *
   * @param array $criteria - criteria    Description of records to
   *   remove.
   * @param array $options - options    Options for remove.    "justOne"
   *    Remove at most one record matching this criteria.
   *
   * @return bool|array - Returns an array containing the status of the
   *   removal if the "w" option is set. Otherwise, returns TRUE.
   */
  <<__Native>>
  public function remove(array $criteria = array(),
                         array $options = array()): mixed;

    /**
   * Update records based on a given criteria
   *
   * @param array $criteria - criteria    Description of the objects to
   *   update.
   * @param array $new_object - new_object    The object with which to
   *   update the matching records.
   * @param array $options - options   
   *
   * @return bool|array - Returns an array containing the status of the
   *   update if the "w" option is set. Otherwise, returns TRUE.
   */
  <<__Native>>
  public function update(array $criteria,
                         array $new_object,
                         array $options = array()): mixed;


  private function getFullName(): string {
    return $this->db . $this->name;
   }
  /**
   * Perform an aggregation using the aggregation framework
   *
   * @param array $pipeline -
   * @param array $op -
   * @param array $... -
   *
   * @return array - The result of the aggregation as an array. The ok
   *   will be set to 1 on success, 0 on failure.
   */
  public function aggregate(array $pipeline): array {
    $cmd = [ 
      'aggregate' => $this->name,
      'pipeline' => $pipeline
    ];
    return $this->db->command($cmd);
  }

  /**
   * Inserts multiple documents into this collection
   *
   * @param array $a - a    An array of arrays or objects. If any objects
   *   are used, they may not have protected or private properties.    If
   *   the documents to insert do not have an _id key or property, a new
   *   MongoId instance will be created and assigned to it. 
   * @param array $options - options    Options for the inserts.  
   *
   * @return mixed - If the w parameter is set to acknowledge the write,
   *   returns an associative array with the status of the inserts ("ok")
   *   and any error that may have occurred ("err"). Otherwise, returns
   *   TRUE if the batch insert was successfully sent, FALSE otherwise.
   */
  public function batchInsert(array $a,
                              array $options = array()): mixed {
    throw Exception("Not Implemented");
  }


  public function __construct(MongoDB $db, string $name) {
    $this->db = $db;
    $this->name = $name;
  }

   /**
   * Counts the number of documents in this collection
   *
   * @param array $query - query    Associative array or object with
   *   fields to match.
   * @param int $limit - limit    Specifies an upper limit to the number
   *   returned.
   * @param int $skip - skip    Specifies a number of results to skip 
   *   before starting the count.
   *
   * @return int - Returns the number of documents matching the query.
   */
  public function count(array $query = array(),
                        int $limit,
                        int $skip): int {
    $cmd = [ 
      'count' => $this->name,
      'query' => $query,
      'limit' => $limit,
      'skip' => $skip
    ];
    $cmd_result = $this->db->command($cmd);
    if (!$cmd_result["ok"]) {
      throw new MongoCursorException();
    }
    return $cmd_result["n"];
  }

  /**
   * Creates a database reference
   *
   * @param mixed $document_or_id - document_or_id    If an array or
   *   object is given, its _id field will be used as the reference ID. If
   *   a MongoId or scalar is given, it will be used as the reference ID.
   *
   * @return array - Returns a database reference array.   If an array
   *   without an _id field was provided as the document_or_id parameter,
   *   NULL will be returned.
   */
  public function createDBRef(string $collection, 
                                mixed $document_or_id): array {
        if (is_array($document_or_id)) {
            $id = $document_or_id['_id'];
        } else {
            $id = $document_or_id;
        }
        return MongoDBRef::create($this->name, $id, $this->db->__getDBName());
    }

  /**
   * Deletes an index from this collection
   *
   * @param string|array $keys - keys    Field or fields from which to
   *   delete the index.
   *
   * @return array - Returns the database response.
   */
  public function deleteIndex(mixed $keys): array {
    $index = $this->toIndexString($keys);
    return $this->db->command(array("deleteIndexes" => $this->getName(), "index" => $index));
  }

  /**
   * Delete all indices for this collection
   *
   * @return array - Returns the database response.
   */
  public function deleteIndexes(): array {
    return $this->deleteIndex("*");
  }

  /**
   * Retrieve a list of distinct values for the given key across a collection.
   *
   * @param string $key -
   * @param array $query -
   *
   * @return array - Returns an array of distinct values,
   */
  public function distinct(string $key,
                           array $query): array {
    return $this->db->command(array("distinct" => $this->name, "key" => $key, "query" => $query));
  }

  /**
   * Drops this collection
   *
   * @return array - Returns the database response.
   */
  public function drop(): array {
    return $this->db->command(array("drop" => $this->name));
  }

  /**
   * Creates an index on the given field(s), or does nothing if the index
   *    already exists
   *  
   *
   * @param string|array $key|keys -
   * @param array $options - options    This parameter is an associative
   *   array of the form array("optionname" => boolean, ...). 
   *
   * @return bool - Returns an array containing the status of the index
   *   creation if the "w" option is set. Otherwise, returns TRUE.
   */
  public function ensureIndex(mixed $key,
                              array $options = array()): bool {
    $indexName = $this->toIndexString($key);
    $client = $this->db->__getClient();

    // check client server version, set newer to true if 2.6+
    $version = $client->getServerVersion();
    $newer = false;
    if (intval($version[0]) > 2) {
      $newer = true;
    } else if (intval($version[0]) == 2) {
      if (intval($version[2]) >= 6) {
        $newer = true;
      }
    }

    // indexOptions Object
    $indexOptions = array("key" => $key,
                          "name" => $indexName,
                          "ns" => $this->name,
                          "background" => $options["background"],
                          "unique" => $options["unique"],
                          "dropDups" => $options["dropDups"],
                          "sparse" => $options["sparse"],
                          "expireAfterSeconds" => $options["expireAfterSeconds"],
                          "v" => $options["v"],
                          "weights" => $options["weights"],
                          "default_language" => $options["default_language"],
                          "language_override" => $options["language_override"]);

    // if server version >= 2.6, can run database command
    if ($newer) {
      $out = $this->db->command(array("createIndexes" => $this->name,
                                      "indexes" => $indexOptions));
    } else {
      $out = $this->db->selectCollection("system.indexes")->insert($indexOptions, 0, true);
    }
    return $out;
  }

  /** TODO
   * Queries this collection, returning a
   *   for the result set
   *
   * @param array $query - query    The fields for which to search.

   * @param array $fields - fields    Fields of the results to return.
   *   The array is in the format array('fieldname' => true, 'fieldname2'
   *   => true). The _id field is always returned.
   *
   * @return MongoCursor - Returns a cursor for the search results.
   */
  public function find(array $query = array(),
                       array $fields = array()): MongoCursor {
    $ns = $this->getFullName();
    return new MongoCursor($this->db->__GetClient(), $ns, $query, $fields);
  }

  /**
   * Update a document and return it
   *
   * @param array $query -
   * @param array $update -
   * @param array $fields -
   * @param array $options -
   *
   * @return array - Returns the original document, or the modified
   *   document when new is set.
   */
  public function findAndModify(array $query,
                                array $update,
                                array $fields,
                                array $options): array {
    $out = $this->db->command(array("findAndModify" => $this->name,
                                    "query" => $query,
                                    "update" => $update,
                                    "fields" => $fields,
                                    "new" => $options["new"],
                                    "upsert" => $options["upsert"],
                                    "sort" => $options["sort"]));
    return $out["value"];
  }

   /**
   * Queries this collection, returning a single element
   *
   * @param array $query - query    The fields for which to search.
   * @param array $fields - fields    Fields of the results to return.
   *   The array is in the format array('fieldname' => true, 'fieldname2'
   *   => true). The _id field is always returned.
   *
   * @return array - Returns record matching the search or NULL.
   */
  public function findOne(array $query = array(),
                          array $fields = array()): array {
    $cursor = $this->find($query, $fields);
    $cursor = $cursor->limit(-1);
    if (!$cursor->hasNext()) {
      return null;
    }
    $cursor->next();
    $ret = $cursor->current();
    if ($cursor->hasNext()) {
      throw new MongoCursorException("findOne has more than 1 result!");
    }
    return $ret;
  }

  /** TODO
   * Gets a collection
   *
   * @param string $name - name    The next string in the collection
   *   name.
   *
   * @return MongoCollection - Returns the collection.
   */
  // <<__Native>>
  // public function __get(string $name): MongoCollection;

  /**
   * Fetches the document pointed to by a database reference
   *
   * @param array $ref - ref    A database reference.
   *
   * @return array - Returns the database document pointed to by the
   *   reference.
   */
  public function getDBRef(array $ref): array {
    return MongoDBRef::get($this->db, $ref);
  }

  /**
   * Returns information about indexes on this collection
   *
   * @return array - This function returns an array in which each element
   *   describes an index. Elements will contain the values name for the
   *   name of the index, ns for the namespace (a combination of the
   *   database and collection name), and key for a list of all fields in
   *   the index and their ordering.
   */
  public function getIndexInfo(): array {
    return $this->db->selectCollection("system.indexes")->findOne(array("ns" => $this->getFullName()));
  }

  /**
   * Returns this collections name
   *
   * @return string - Returns the name of this collection.
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * Get the read preference for this collection
   *
   * @return array -
   */
  public function getReadPreference(): array {
    return $this->read_preference;
  }

  /**
   * Get slaveOkay setting for this collection
   *
   * @return bool - Returns the value of slaveOkay for this instance.
   */
  public function getSlaveOkay(): bool {
    return $this->slaveOkay;
  }

  /** TODO
   * Performs an operation similar to SQL's GROUP BY command
   *
   * @param mixed $keys - keys    Fields to group by. If an array or
   *   non-code object is passed, it will be the key used to group results.
   * @param array $initial - initial    Initial value of the aggregation
   *   counter object.
   * @param mongocode $reduce - reduce    A function that takes two
   *   arguments (the current document and the aggregation to this point)
   *   and does the aggregation.
   * @param array $options - options    Optional parameters to the group
   *   command.
   *
   * @return array - Returns an array containing the result.
   */
  // <<__Native>>
  // public function group(mixed $keys,
  //                       array $initial,
  //                       MongoCode $reduce,
  //                       array $options = array()): array;

  

   /**
   * Saves a document to this collection
   *
   * @param array|object $a - a    Array or object to save. If an object
   *   is used, it may not have protected or private properties.    If the
   *   parameter does not have an _id key or property, a new MongoId
   *   instance will be created and assigned to it.
   * @param array $options - options    Options for the save.
   *
   * @return mixed - If w was set, returns an array containing the status
   *   of the save. Otherwise, returns a boolean representing if the array
   *   was not empty (an empty array will not be inserted).
   */
  public function save(mixed $a,
                       array $options = array()): mixed {
    if (is_array($a)) {
      if (!isset($a["_id"])) {
        $a["_id"] = new MongoId();
        return $this->insert($a);
      }
      return $this->update(array("_id" => $a["_id"]), $a);
    }
    throw new Exception("Saving objects not implemented");
  }

  /**
   * Set the read preference for this collection
   *
   * @param string $read_preference -
   * @param array $tags -
   *
   * @return bool -
   */
  public function setReadPreference(string $read_preference,
                                    array $tags): bool {
    $this->read_preference['type'] = $read_preference;
    $this->read_preference['tagsets'] = $tags;
    return true;
  }

  /**
   * Change slaveOkay setting for this collection
   *
   * @param bool $ok - ok    If reads should be sent to secondary members
   *   of a replica set for all possible queries using this MongoCollection
   *   instance.
   *
   * @return bool - Returns the former value of slaveOkay for this
   *   instance.
   */
  public function setSlaveOkay(bool $ok = true): bool {
    $former = $this->slaveOkay;
    $this->slaveOkay = $ok;
    return $former;
  }

  /**DEPRECATED 
   * TODO: Enforce only scalar value types
   * Converts keys specifying an index to its identifying string
   *
   * @param mixed $keys - keys    Field or fields to convert to the
   *   identifying string
   *
   * @return string - Returns a string that describes the index.
   */
  static protected function toIndexString(mixed $keys): string {
    $str = "";
    
    if (gettype($keys) == "array") {

      foreach ($keys as $key => $val) {        
        // order must be ascending (1) for boolean field
        if (gettype($val) == "boolean")
          $val = 1;

        if ($key === key($keys)) {
          $str .= $key . "_" . $val;
        }
        else {
          $str .= "_" . $key . "_" . $val;
        }
      }
    } else {
      // if $keys is just a string, append _1 as value
      $str .= $keys . "_1";
    }
    return $str;
  }

  /**
   * String representation of this collection
   *
   * @return string - Returns the full name of this collection.
   */
  public function __toString(): string {
    return $this->name;
  }

  /**
   * Validates this collection
   *
   * @param bool $scan_data - scan_data    Only validate indices, not the
   *   base collection.
   *
   * @return array - Returns the databases evaluation of this object.
   */
  public function validate(bool $scan_data = false): array {
    $cmd = array("validate" => $this->name);
    if ($scan_data) {
      $cmd["full"] = true;
    }

    return $this->db->command($cmd);
  }
}