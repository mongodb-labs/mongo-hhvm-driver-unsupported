<?hh

/**
 * A unique identifier created for database objects. If an object is inserted
 * into the database without an _id field, an _id field will be added to it
 * with a MongoId instance as its value. If the data has a naturally occuring
 * unique field (say, a username or timestamp) it is fine to use this as the
 * _id field instead, and it will not be replaced with a MongoId.   Instances
 * of the MongoId class fulfill the role that autoincrementing does in a
 * relational database: to provide a unique key if the data does not natually
 * have one. Autoincrementing does not work well with a sharded database, as
 * it is impossible to find what the next number should be quickly. This class
 * fulfills the constraints of quickly generating a value that is unique
 * across shards.   Each MongoId is 12 bytes (making its string form 24
 * hexidecimal characters). The first four bytes are a timestamp, the next
 * three are a hash of the client machine's hostname, the next two are the two
 * least significant bytes of the process id running the script, and the last
 * three bytes are an incrementing value.   MongoIds are
 * serializable/unserializable. Their serialized form is similar to their
 * string form:
 */
class MongoId {
  /**
   * Creates a new id
   *
   * @param string $id - id    A string to use as the id. Must be 24
   *   hexidecimal characters. If an invalid string is passed to this
   *   constructor, the constructor will ignore it and create a new id
   *   value.
   *
   * @return  - Returns a new id.
   */

  public string $id = NULL;
  
  <<__Native>>
  public function __construct(string $id = NULL): void;

  /**
   * Gets the hostname being used for this machine's ids
   *
   * @return string - Returns the hostname.
   */
  <<__Native>>
  public static function getHostname(): string;

  /**
   * Gets the incremented value to create this id
   *
   * @return int - Returns the incremented value used to create this
   *   MongoId.
   */
  <<__Native>>
  public function getInc(): int;

  /**
   * Gets the process ID
   *
   * @return int - Returns the PID of the MongoId.
   */
  <<__Native>>
  public function getPID(): int;

  /**
   * Gets the number of seconds since the epoch that this id was created
   *
   * @return int - Returns the number of seconds since the epoch that
   *   this id was created. There are only four bytes of timestamp stored,
   *   so MongoDate is a better choice for storing exact or wide-ranging
   *   times.
   */
  <<__Native>>
  public function getTimestamp(): int;

  /**
   * Check if a value is a valid ObjectId
   *
   * @param mixed $value - value    The value to check for validity.
   *
   * @return bool - Returns TRUE if value is a MongoId instance or a
   *   string consisting of exactly 24 hexadecimal characters; otherwise,
   *   FALSE is returned.
   */
  <<__Native>>
  public static function isValid(mixed $value): bool;

  /**
   * Create a dummy MongoId
   *
   * @param array $props - props    Theoretically, an array of properties
   *   used to create the new id. However, as MongoId instances have no
   *   properties, this is not used.
   *
   * @return MongoId - A new id with the value
   *   "000000000000000000000000".
   */
  <<__Native>>
  public static function __set_state(array $props): MongoId;

  /**
   * Returns a hexidecimal representation of this id
   *
   * @return string - This id.
   */
  <<__Native>>
  public function __toString(): string;

}