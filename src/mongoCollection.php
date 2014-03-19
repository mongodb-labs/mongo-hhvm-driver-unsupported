<?hh

/**
 * Represents a MongoDB collection.   Collection names can use any character
 * in the ASCII set. Some valid collection names are , ..., my collection, and
 * *#@.   User-defined collection names cannot contain the $ symbol. There are
 * certain system collections which use a $ in their names (e.g.,
 * local.oplog.$main), but it is a reserved character. If you attempt to
 * create and use a collection with a $ in the name, MongoDB will assert.
 */
class MongoCollection {

  /* Constants */
  const ASCENDING = 1 ;
  const DESCENDING = -1 ;
  /* Fields */

  public $db = NULL;
  public $w;
  public $wtimeout;

  private $name;

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
  <<__Native>>
  public function aggregate(array $pipeline): array;

  /**
   * Inserts multiple documents into this collection
   *
   * @param array $a - a    An array of arrays or objects. If any objects
   *   are used, they may not have protected or private properties.    If
   *   the documents to insert do not have an _id key or property, a new
   *   MongoId instance will be created and assigned to it. See
   *   MongoCollection::insert() for additional information on this
   *   behavior.
   * @param array $options - options    Options for the inserts.  
   *   "continueOnError"   Boolean, defaults to FALSE. If set, the database
   *   will not stop processing a bulk insert if one fails (eg due to
   *   duplicate IDs). This makes bulk insert behave similarly to a series
   *   of single inserts, except that calling MongoDB::lastError() will
   *   have an error set if any insert fails, not just the last one. If
   *   multiple errors occur, only the most recent will be reported by
   *   MongoDB::lastError().    Please note that continueOnError affects
   *   errors on the database side only. If you try to insert a document
   *   that has errors (for example it contains a key with an empty name),
   *   then the document is not even transferred to the database as the
   *   driver detects this error and bails out. continueOnError has no
   *   effect on errors detected in the documents by the driver.
   *
   * @return mixed - If the w parameter is set to acknowledge the write,
   *   returns an associative array with the status of the inserts ("ok")
   *   and any error that may have occurred ("err"). Otherwise, returns
   *   TRUE if the batch insert was successfully sent, FALSE otherwise.
   */
  <<__Native>>
  public function batchInsert(array $a,
                              array $options = array()): mixed;

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
  <<__Native>>
  public function count(array $query = array(),
                        int $limit,
                        int $skip): int;

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
  <<__Native>>
  public function createDBRef(mixed $document_or_id): array;

  /**
   * Deletes an index from this collection
   *
   * @param string|array $keys - keys    Field or fields from which to
   *   delete the index.
   *
   * @return array - Returns the database response.
   */
  <<__Native>>
  public function deleteIndex(mixed $keys): array;

  /**
   * Delete all indices for this collection
   *
   * @return array - Returns the database response.
   */
  <<__Native>>
  public function deleteIndexes(): array;

  /**
   * Retrieve a list of distinct values for the given key across a collection.
   *
   * @param string $key -
   * @param array $query -
   *
   * @return array - Returns an array of distinct values,
   */
  <<__Native>>
  public function distinct(string $key,
                           array $query): array;

  /**
   * Drops this collection
   *
   * @return array - Returns the database response.
   */
  <<__Native>>
  public function drop(): array;

  /**
   * Creates an index on the given field(s), or does nothing if the index
   *    already exists
   *  
   *
   * @param string|array $key|keys -
   * @param array $options - options    This parameter is an associative
   *   array of the form array("optionname" => boolean, ...). Currently
   *   supported options are:    "unique"   Create a unique index.    A
   *   unique index cannot be created on a field if multiple existing
   *   documents do not contain the field. The field is effectively NULL
   *   for these documents and thus already non-unique. Sparse indexing may
   *   be used to overcome this, since it will prevent documents without
   *   the field from being indexed.      "dropDups"   If a unique index is
   *   being created and duplicate values exist, drop all but one duplicate
   *   value.     "sparse"   Create a sparse index, which only includes
   *   documents containing the field. This option is only compatible with
   *   single-field indexes.     "expireAfterSeconds"   The value of this
   *   option should specify the number of seconds after which a document
   *   should be considered expired and automatically removed from the
   *   collection. This option is only compatible with single-field indexes
   *   where the field will contain MongoDate values.   This feature is
   *   available in MongoDB 2.2+. See Expire Data from Collections by
   *   Setting TTL for more information.     "background"   By default,
   *   index creation is a blocking operation and will stop other
   *   operations on the database from proceeding until completed. If you
   *   specify TRUE for this option, the index will be created in the
   *   background while other operations are taking place.    Prior to
   *   MongoDB 2.1.0, the index build operation is not a background build
   *   when it replicates to secondaries, irrespective of this option. See
   *   Building Indexes with Replica Sets for more information.      "name"
   *     This option allows you to override the algorithm that the driver
   *   uses to create an index name and specify your own. This can be
   *   useful if you are indexing many keys and Mongo complains about the
   *   index name being too long.
   *
   * @return bool - Returns an array containing the status of the index
   *   creation if the "w" option is set. Otherwise, returns TRUE.   Fields
   *   in the status array are described in the documentation for
   *   MongoCollection::insert().
   */
  <<__Native>>
  public function ensureIndex(mixed $key,
                              array $options = array()): bool;

  /** TODO
   * Queries this collection, returning a
   *   for the result set
   *
   * @param array $query - query    The fields for which to search.
   *   MongoDB's query language is quite extensive. The PHP driver will in
   *   almost all cases pass the query straight through to the server, so
   *   reading the MongoDB core docs on find is a good idea.    Please make
   *   sure that for all special query operators (starting with $) you use
   *   single quotes so that PHP doesn't try to replace "$exists" with the
   *   value of the variable $exists.
   * @param array $fields - fields    Fields of the results to return.
   *   The array is in the format array('fieldname' => true, 'fieldname2'
   *   => true). The _id field is always returned.
   *
   * @return MongoCursor - Returns a cursor for the search results.
   */
  // <<__Native>>
  // public function find(array $query = array(),
  //                      array $fields = array()): MongoCursor;

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
  <<__Native>>
  public function findAndModify(array $query,
                                array $update,
                                array $fields,
                                array $options): array;

   /**
   * Queries this collection, returning a single element
   *
   * @param array $query - query    The fields for which to search.
   *   MongoDB's query language is quite extensive. The PHP driver will in
   *   almost all cases pass the query straight through to the server, so
   *   reading the MongoDB core docs on find is a good idea.    Please make
   *   sure that for all special query operaters (starting with $) you use
   *   single quotes so that PHP doesn't try to replace "$exists" with the
   *   value of the variable $exists.
   * @param array $fields - fields    Fields of the results to return.
   *   The array is in the format array('fieldname' => true, 'fieldname2'
   *   => true). The _id field is always returned.
   *
   * @return array - Returns record matching the search or NULL.
   */
  <<__Native>>
  public function findOne(array $query = array(),
                          array $fields = array()): array;

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
  <<__Native>>
  public function getDBRef(array $ref): array;

  /**
   * Returns information about indexes on this collection
   *
   * @return array - This function returns an array in which each element
   *   describes an index. Elements will contain the values name for the
   *   name of the index, ns for the namespace (a combination of the
   *   database and collection name), and key for a list of all fields in
   *   the index and their ordering. Additional values may be present for
   *   special indexes, such as unique or sparse.
   */
  <<__Native>>
  public function getIndexInfo(): array;

  /**
   * Returns this collections name
   *
   * @return string - Returns the name of this collection.
   */
  <<__Native>>
  public function getName(): string;

  /**
   * Get the read preference for this collection
   *
   * @return array -
   */
  <<__Native>>
  public function getReadPreference(): array;

  /**
   * Get slaveOkay setting for this collection
   *
   * @return bool - Returns the value of slaveOkay for this instance.
   */
  <<__Native>>
  public function getSlaveOkay(): bool;

  /** TODO
   * Performs an operation similar to SQL's GROUP BY command
   *
   * @param mixed $keys - keys    Fields to group by. If an array or
   *   non-code object is passed, it will be the key used to group results.
   *    1.0.4+: If keys is an instance of MongoCode, keys will be treated
   *   as a function that returns the key to group by (see the "Passing a
   *   keys function" example below).
   * @param array $initial - initial    Initial value of the aggregation
   *   counter object.
   * @param mongocode $reduce - reduce    A function that takes two
   *   arguments (the current document and the aggregation to this point)
   *   and does the aggregation.
   * @param array $options - options    Optional parameters to the group
   *   command. Valid options include:     "condition"   Criteria for
   *   including a document in the aggregation.     "finalize"   Function
   *   called once per unique key that takes the final output of the reduce
   *   function.
   *
   * @return array - Returns an array containing the result.
   */
  // <<__Native>>
  // public function group(mixed $keys,
  //                       array $initial,
  //                       MongoCode $reduce,
  //                       array $options = array()): array;

  /**
   * Inserts a document into the collection
   *
   * @param array|object $a - a    An array or object. If an object is
   *   used, it may not have protected or private properties.    If the
   *   parameter does not have an _id key or property, a new MongoId
   *   instance will be created and assigned to it. This special behavior
   *   does not mean that the parameter is passed by reference.
   * @param array $options - options    Options for the insert.
   *
   * @return bool|array - Returns an array containing the status of the
   *   insertion if the "w" option is set. Otherwise, returns TRUE if the
   *   inserted array is not empty (a MongoException will be thrown if the
   *   inserted array is empty).   If an array is returned, the following
   *   keys may be present:    ok    This should almost always be 1 (unless
   *   last_error itself failed).      err    If this field is non-null, an
   *   error occurred on the previous operation. If this field is set, it
   *   will be a string describing the error that occurred.      code    If
   *   a database error occurred, the relevant error code will be passed
   *   back to the client.      errmsg    This field is set if something
   *   goes wrong with a database command. It is coupled with ok being 0.
   *   For example, if w is set and times out, errmsg will be set to "timed
   *   out waiting for slaves" and ok will be 0. If this field is set, it
   *   will be a string describing the error that occurred.      n    If
   *   the last operation was an update, upsert, or a remove, the number of
   *   documents affected will be returned. For insert operations, this
   *   value is always 0.      wtimeout    If the previous option timed out
   *   waiting for replication.      waited    How long the operation
   *   waited before timing out.      wtime    If w was set and the
   *   operation succeeded, how long it took to replicate to w servers.   
   *    upserted    If an upsert occurred, this field will contain the new
   *   record's _id field. For upserts, either this field or
   *   updatedExisting will be present (unless an error occurred).    
   *   updatedExisting    If an upsert updated an existing element, this
   *   field will be true. For upserts, either this field or upserted will
   *   be present (unless an error occurred).
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
   *   removal if the "w" option is set. Otherwise, returns TRUE.   Fields
   *   in the status array are described in the documentation for
   *   MongoCollection::insert().
   */
  <<__Native>>
  public function remove(array $criteria = array(),
                         array $options = array()): mixed;

   /**
   * Saves a document to this collection
   *
   * @param array|object $a - a    Array or object to save. If an object
   *   is used, it may not have protected or private properties.    If the
   *   parameter does not have an _id key or property, a new MongoId
   *   instance will be created and assigned to it. See
   *   MongoCollection::insert() for additional information on this
   *   behavior.
   * @param array $options - options    Options for the save.
   *
   * @return mixed - If w was set, returns an array containing the status
   *   of the save. Otherwise, returns a boolean representing if the array
   *   was not empty (an empty array will not be inserted).
   */
  <<__Native>>
  public function save(mixed $a,
                       array $options = array()): mixed;

  /**
   * Set the read preference for this collection
   *
   * @param string $read_preference -
   * @param array $tags -
   *
   * @return bool -
   */
  <<__Native>>
  public function setReadPreference(string $read_preference,
                                    array $tags): bool;

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
  <<__Native>>
  public function setSlaveOkay(bool $ok = true): bool;

  /**
   * Converts keys specifying an index to its identifying string
   *
   * @param mixed $keys - keys    Field or fields to convert to the
   *   identifying string
   *
   * @return string - Returns a string that describes the index.
   */
  <<__Native>>
  static protected function toIndexString(mixed $keys): string;

  /**
   * String representation of this collection
   *
   * @return string - Returns the full name of this collection.
   */
  <<__Native>>
  public function __toString(): string;

  /**
   * Update records based on a given criteria
   *
   * @param array $criteria - criteria    Description of the objects to
   *   update.
   * @param array $new_object - new_object    The object with which to
   *   update the matching records.
   * @param array $options - options    This parameter is an associative
   *   array of the form array("optionname" => boolean, ...). Currently
   *   supported options are:    "upsert"   If no document matches
   *   $criteria, a new document will be inserted.   If a new document
   *   would be inserted and $new_object contains atomic modifiers (i.e. $
   *   operators), those operations will be applied to the $criteria
   *   parameter to create the new document. If $new_object does not
   *   contain atomic modifiers, it will be used as-is for the inserted
   *   document. See the upsert examples below for more information.   
   *   "multiple"   All documents matching $criteria will be updated.
   *   MongoCollection::update() has exactly the opposite behavior of
   *   MongoCollection::remove(): it updates one document by default, not
   *   all matching documents. It is recommended that you always specify
   *   whether you want to update multiple documents or a single document,
   *   as the database may change its default behavior at some point in the
   *   future.
   *
   * @return bool|array - Returns an array containing the status of the
   *   update if the "w" option is set. Otherwise, returns TRUE.   Fields
   *   in the status array are described in the documentation for
   *   MongoCollection::insert().
   */
  <<__Native>>
  public function update(array $criteria,
                         array $new_object,
                         array $options = array()): mixed;

  /**
   * Validates this collection
   *
   * @param bool $scan_data - scan_data    Only validate indices, not the
   *   base collection.
   *
   * @return array - Returns the databases evaluation of this object.
   */
  <<__Native>>
  public function validate(bool $scan_data = false): array;
}
