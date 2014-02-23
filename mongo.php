<?hh

class MongoClient {

	/* Constants */

	//PHP driver version. May be suffixed with "dev", "+" or "-" if it is in-between versions. 
	const VERSION ; 
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

	/* Methods */
	/**
    * @param optional string, optional array
    * @return bool
    */
	public function __construct ($server = "mongodb://localhost:27017", $options = array("connect" => TRUE));
	
	/**
    * @param boolean|string
    * @return bool
    */
	public function close($connection);

	/**
    * @return bool
    */
	public function connect();

	/**
	* @param mixed
    * @return bool
    */
	public function dropDB($db);

	/**
	* @param string
    * @return MongoDB
    */
	public function __get ($dbname);

	/**
    * @return array
    */
	public static function getConnections();

	/**
    * @return array
    */
	public function getHosts ();

	/**
    * @return array
    */
	public function getReadPreference();

	/**
	* @param string, int|MongoInt64
    * @return bool
    */
	public function killCursor($server_hash, $id);

	/**
    * @return array
    */
	public function listDBs();

	/**
	* @param string, string
    * @return MongoCollection
    */
	public function selectCollection($db, $collection);

	/**
	* @param string
    * @return MongoDB
    */
	public function selectDB ($name);

	/**
	* @param string, (optional array)
    * @return bool
    */
	public function setReadPreference ($read_preference, $tags = NULL)

	/**
    * @return bool
    */
	public function __toString();
}
