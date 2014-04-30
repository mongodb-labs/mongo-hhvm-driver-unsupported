<?hh
/**
 * A connection manager for PHP and MongoDB.   This class is used to create
 * and manage connections.
 */
class MongoClient {

  const RP_PRIMARY   = 'primary';
  const RP_PRIMARY_PREFERRED = 'primaryPreferred';
  const RP_SECONDARY = 'secondary';
  const RP_SECONDARY_PREFERRED = 'secondaryPreferred';
  const RP_NEAREST   = 'nearest';

  private $read_preference = [];
  private $databases = [];

  <<__Native>>
  public function __construct (string $server = "mongodb://localhost:27017", 
                                array $options = array('connect' => true)): void;

  /**
   * Closes this connection
   *
   * @param boolean|string $connection - connection    If connection is
   *   not given, or FALSE then connection that would be selected for
   *   writes would be closed. If
   *   connection is TRUE then all connections as known by the connection
   *   manager will be closed. If connection is a string argument, then
   *   it will only close the connection identified by this hash. 
   *
   * @return bool - Returns if the connection was successfully closed.
   */
  <<__Native>>
  public function close(mixed $connection): bool;

  /**
   * Connects to a database server
   *
   * @return bool - If the connection was successful.
   */
  <<__Native>>
  public function connect(): bool;

  /**
   * Drops a database [deprecated]
   *
   * @param mixed $db - db    The database to drop. Can be a MongoDB
   *   object or the name of the database.
   *
   * @return array - Returns the database response.
   */
  public function dropDB(mixed $db): array {
    if(is_object($db)) {
      return $db->drop();
    } 

    if(is_string($db)) {
      return $this->selectDB($db)->drop();
    }
    throw new MongoConnectionException();
  }

  /**
   * Gets a database
   *
   * @param string $dbname - dbname    The database name.
   *
   * @return MongoDB - Returns a new db object.
   */
  public function __get(string $dbname): MongoDB {
    return $this->selectDB($dbname);
  }

  /**
   * Return info about all open connections
   *
   * @return array - An array of open connections.
   */
  <<__Native>>
  public static function getConnections(): array;

  /**
   * Updates status for all associated hosts
   *
   * @return array - Returns an array of information about the hosts in
   *   the set.
   */
  <<__Native>>
  public function getHosts(): array;

  /**
   * Get the read preference for this connection
   *
   * @return array -
   */
  public function getReadPreference(): array {
    return $this->read_preference;
  }

  /**
   * Kills a specific cursor on the server
   *
   * @param string $server_hash - server_hash    The server hash that has
   *   the cursor. This can be obtained through MongoCursor::info.
   * @param int|mongoint64 $id - id    The ID of the cursor to kill.
   *
   * @return bool - Returns TRUE if the method attempted to kill a
   *   cursor, and FALSE if there was something wrong with the arguments
   *   (such as a wrong server_hash).
   */
  <<__Native>>
  public function killCursor(string $server_hash,
                           mixed $id): bool;

  /**
   * Lists all of the databases available.
   *
   * @return array - Returns an associative array containing three
   *   fields. The first field is databases, which in turn contains an
   *   array. Each element of the array is an associative array
   *   corresponding to a database, giving th database's name, size, and if
   *   it's empty. The other two fields are totalSize (in bytes) and ok,
   *   which is 1 if this method ran successfully.
   */
  public function listDBs(): array {
    return $this->selectDB('admin')->command(array("listDatabases" => 1));
  }

  /**
   * Gets a database collection
   *
   * @param string $db - db    The database name.
   * @param string $collection - collection    The collection name.
   *
   * @return MongoCollection - Returns a new collection object.
   */
  public function selectCollection(string $db,
                                   string $collection) {
    return $this->selectDB($db)->selectCollection($collection);
  }

  /**
   * Gets a database
   *
   * @param string $name - name    The database name.
   *
   * @return MongoDB - Returns a new database object.
   */
  public function selectDB(string $name): MongoDB {
    if (!isset($this->databases[$name])) {
      $this->databases[$name] = new MongoDB($this, $name);
    } 
    return $this->databases[$name];
  }

  /**
   * Set the read preference for this connection
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
   * String representation of this connection
   *
   * @return string - Returns hostname and port for this connection.
   */
  <<__Native>>
  public function __toString(): string;

  /**
   * Test method that returns the server's version string
   *
   * @return string
   */
  <<__Native>>
  public function getServerVersion(): string;
}