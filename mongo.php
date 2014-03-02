<?hh

<<__Native>>
function example_sum(int $a, int $b): int;

<<__Native>>
function hello_world(string $statement): string;

<<__Native>>
function sum_all(array<int> $numberArray): int;

class MongoClient {

    /* Constants */

    //PHP driver version. May be suffixed with "dev", "+" or "-" if it is in-between versions. 
    const VERSION = ""; 
    const DEFAULT_HOST = "localhost" ;
    const DEFAULT_PORT = 27017 ;
    const RP_PRIMARY = "primary" ;
    const RP_PRIMARY_PREFERRED = "primaryPreferred" ;
    const RP_SECONDARY = "secondary" ;
    const RP_SECONDARY_PREFERRED = "secondaryPreferred" ;
    const RP_NEAREST = "nearest" ;

    /* Properties */
    public $connected = FALSE ;
    public $status = NULL ;
    protected $server = NULL ;
    protected $persistent = NULL ;

    private $options;
    private $host;
    private $port;

    /* Methods */
    
    /**
    * @param optional string, optional array
    * @return bool
    */
    public function __construct ($server = "mongodb://localhost:27017", 
                                 $options = array("connect" => TRUE)): bool {
        return false;
    }
    
    /**
    * @param boolean|string
    * @return bool
    */
    public function close($connection): bool {
        return false;
    }

    /**
    * @return bool
    */
    public function connect(): bool {
        return false;
    }

    /**
    * @param mixed
    * @return bool
    */
    public function dropDB($db): bool {
        return false;
    }

    /**
    * @param string
    * @return MongoDB
    */
    // public function __get ($dbname);

    /**
    * @return array
    */
    <<__Native>>
    public static function getConnections(): array;

    /**
    * @return array
    */
    <<__Native>>
    public function getHosts(): array;

    /**
    * @return array
    */
    public function getReadPreference(): array {
        return array(false);
    }

    /**
    * @param string, int|MongoInt64
    * @return bool
    */
    public function killCursor($server_hash, $id): bool {
        return false;
    }

    /**
    * @return array
    */
    public function listDBs(): array {
        return array(false);
    }

    /**
    * @param string, string
    * @return MongoCollection
    */
    // public function selectCollection($db, $collection);

    /**
    * @param string
    * @return MongoDB
    */
    // public function selectDB ($name);

    /**
    * @param string, (optional array)
    * @return bool
    */
    public function setReadPreference ($read_preference, $tags = NULL): bool {
        return false;
    }

    /**
    * @return string
    */
    public function __toString(): string {
        return "NotImplemented";
    }
}

