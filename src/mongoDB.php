<?hh

/**
 * Instances of this class are used to interact with a database.
 */
class MongoDB {
    
    /* variables */
    private $client = null;
    private $collections = [];
    private $db_name = "";
    private $slaveOkay = false;
    private $read_preference = [];
    private $write_concern = [];

    /**
     * Log in to this database
     *
     * @param string $username - username    The username.
     * @param string $password - password    The password (in plaintext).
     *
     * @return array - Returns database response. If the login was
     *   successful, it will return    If something went wrong, it will
     *   return    ("auth fails" could be another message, depending on
     *   database version and what when wrong).
     */
    public function authenticate(string $username,
                                string $password): array {
        $salted = "${username}:mongo:${password}";
        $hash = md5($salted);

        $nonce = $this->command(array("getnonce" => 1));

        $saltedHash = md5($nonce["nonce"]."${username}${hash}");
        
        $result = $this->command(array("authenticate" => 1,
                                       "user" => $username,
                                       "nonce" => $nonce["nonce"],
                                       "key" => $saltedHash));

        return $result;
    }

    /**
     * Execute a database command
     *
     * @param array $command - command    The query to send.
     * @param array $options - options    
     *
     * @return array - Returns database response. Every database response
     *   is always maximum one document, which means that the result of a
     *   database command can never exceed 16MB.
     */
    public function command(array $command,
                            array $options = array()): array {
        return $this->selectCollection("$cmd")->findOne($command);
    }

    /**
     * Creates a new database
     *
     * @param mongoclient $conn - MongoClient conn    Database connection.
     * @param string $name - name    Database name.
     *
     * @return  - Returns the database.
     */

    //TODO: Make native
    public function __construct(MongoClient $conn, string $name) {
            $this->client = $conn;
            $this->db_name = $name;
    }

    /**
     * Creates a collection
     *
     * @param string $name - name    The name of the collection.
     * @param array $options - options    An array containing options for
     *   the collections. Each option is its own element in the options
     *   array, with the option name listed below being the key of the
     *   element. 
     *
     * @return MongoCollection - Returns a collection object representing
     *   the new collection.
     */
    public function createCollection(string $name,
                                      array $options): MongoCollection {
        $result = $this->command(array(
            "create" => $name,
            "capped" => $options["capped"],
            "size" => $options["size"],
            "max" => $options["max"],
            "autoIndexId" => $options["autoIndexId"],
        ));
        if (!$result["ok"]) {
            throw new MongoException("Unable to create collection");
        }
        return new MongoCollection($this, $name);   
    }


    /** TODO
     * Creates a database reference
     *
     * @param string $collection - collection    The collection to which
     *   the database reference will point.
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
        return MongoDBRef::create($collection, $id, $this->db_name);
    }

    /**
     * Drops this database
     *
     * @return array - Returns the database response.
     */
    public function drop(): array {
        return $this->command(array("dropDatabase" => 1));
    }

    /**
     * Drops a collection [deprecated].
     *
     * @param mixed $coll - coll    MongoCollection or name of collection
     *   to drop.
     *
     * @return array - Returns the database response.
     */
    public function dropCollection(mixed $coll): array {
        if (is_object($coll)) {
            $coll = (string)$coll;
        }
        return $this->command(array('drop' => $coll));
    }

    /**
     * Runs JavaScript code on the database server.
     *
     * @param mixed $code - code    MongoCode or string to execute.
     * @param array $args - args    Arguments to be passed to code.
     *
     * @return array - Returns the result of the evaluation.
     */
    public function execute(mixed $code, array $args = array()): array {
        return $this->command(array('eval' => $code, 'args' => $args));
    }


    /**
     * Creates a database error
     *
     * @return bool - Returns the database response.
     */
    public function forceError(): bool {
        return $this->command(array('forceerror' => 1));
    }

    /**
     * Gets a collection
     *
     * @param string $name - name    The name of the collection.
     *
     * @return MongoCollection - Returns the collection.
     */
    public function __get(string $name): MongoCollection {
        return $this->selectCollection($name);
    }

    public function __getDBName(): string {
        return $this->db_name;
    }

    public function __getClient(): MongoClient {
        return $this->client;
    }

    /** TODO: Handle system collections
     * Get all collections from this database
     *
     * @param bool $includeSystemCollections -
     *
     * @return array - Returns the names of the all the collections in the
     *   database as an array.
     */
    public function getCollectionNames(bool $includeSystemCollections = false): array {
        $allCollections = [];
        $db_name_length = strlen($this->db_name) + 1;
        $c = $this->selectCollection("system.namespaces")->find();
        while ($c->hasNext()) {
            $c->next();
            $curr_coll = $c->current();
            $name = $curr_coll["name"];

            if (strpos("$", $name) >= 0 && strpos(".oplog.$") === false) {
                continue;
            }

            $allCollections[] = substr($name, $db_name_length);
        }
        sort($allCollections);
        return $allCollections;
    }

    /**
     * Fetches the document pointed to by a database reference
     *
     * @param array $ref - ref    A database reference.
     *
     * @return array - Returns the document pointed to by the reference.
     */
    public function getDBRef(array $ref): array {
        return MongoDBRef::get($this, $ref);
    }

    /**
     * TODO: Make MongoGridFS class
     * Fetches toolkit for dealing with files stored in this database
     *
     * @param string $prefix - prefix    The prefix for the files and
     *   chunks collections.
     *
     * @return MongoGridFS - Returns a new gridfs object for this database.
     */
    // <<__Native>>
    // public function getGridFS(string $prefix = fs): MongoGridFS;

    /**
     * Gets this databases profiling level
     *
     * @return int - Returns the profiling level.
     */
    public function getProfilingLevel(): int {
        return $this->command(array('profile' => -1));
    }

    /**
     * Get the read preference for this database
     *
     * @return array -
     */
    public function getReadPreference(): array {
        return $this->read_preference;
    }

    /**
     * Get slaveOkay setting for this database
     *
     * @return bool - Returns the value of slaveOkay for this instance.
     */
    public function getSlaveOkay(): bool{
        return $this->slaveOkay;
    }

    /**
     * Check if there was an error on the most recent db operation performed
     *
     * @return array - Returns the error, if there was one.
     */
    public function lastError(): array {
        return $this->command(array('getLastError' => 1));
    }

    /** TODO: handle system collections
     * Gets an array of all MongoCollections for this database
     *
     * @param bool $includeSystemCollections -
     *
     * @return array - Returns an array of MongoCollection objects.
     */
    public function listCollections(bool $includeSystemCollections = false): array {
        $collection_names = $this->getCollectionNames();
        foreach ($collection_names as $name) {
            if (!isset($this->collections[$name])) {
                $this->collections[$name] = new MongoCollection($this, $name);
            }
        }
        return $this->collections;
    }

    /** TODO: Will be deprecated soon
     * Checks for the last error thrown during a database operation
     *
     * @return array - Returns the error and the number of operations ago
     *   it occurred.
     */
    public function prevError(): array {
        return $this->command(array('getPrevError' => 1));
    }

    /**
     * Repairs and compacts this database
     *
     * @param bool $preserve_cloned_files - preserve_cloned_files    If
     *   cloned files should be kept if the repair fails.
     * @param bool $backup_original_files - backup_original_files    If
     *   original files should be backed up.
     *
     * @return array - Returns db response.
     */
    public function repair(bool $preserve_cloned_files = false,
                            bool $backup_original_files = false): array {
        return $this->command(array('repairDatabase' => 1));
    }

    /**
     * Clears any flagged errors on the database
     *
     * @return array - Returns the database response.
     */
    public function resetError(): array {
        return $this->command(array('reseterror' => 1));
    }

    /**
     * Gets a collection
     *
     * @param string $name - name    The collection name.
     *
     * @return MongoCollection - Returns a new collection object.
     */
    public function selectCollection(string $name): MongoCollection {
        if (!isset($this->collections[$name])) {
            $this->collections[$name] = new MongoCollection($this, $name);
        }
        return $this->collections[$name];
    }

    /**
     * Sets this databases profiling level
     *
     * @param int $level - level    Profiling level.
     *
     * @return int - Returns the previous profiling level.
     */
    public function setProfilingLevel(int $level): int {
        return $this->command(array('profile' => $level));
    }

    /** TODO: When to return false?
     * Set the read preference for this database
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
     * Change slaveOkay setting for this database
     *
     * @param bool $ok - ok    If reads should be sent to secondary members
     *   of a replica set for all possible queries using this MongoDB
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

    public function setWriteConcern(mixed $w, int $timeout = 10000) {
        if( !is_integer($w) || !is_string($w)) {
            throw Exception("Invalid argument to set write concern");
        }
        $this->writeConcern["w"] = $w;
        if (!isset($this->writeConcern["timeout"])) {
            $this->writeConcern["timeout"] = $timeout;
        }
    }

    /**
     * The name of this database
     *
     * @return string - Returns this databases name.
     */
    public function __toString(): string {
        return $this->db_name;
    }

}
