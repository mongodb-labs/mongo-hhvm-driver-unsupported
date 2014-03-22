<?hh

/**
* Thrown when a write fails. 
*/
class MongoWriteConcernException extends MongoCursorException
{
	public function getDocument() {
		throw new Exception('Not Implemented');
	}
}