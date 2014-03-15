<?hh

/**
* Thrown when attempting to insert a document into a collection which
* already contains the same values for the unique keys.
*/
class MongoDuplicateKeyException extends MongoWriteConcernException
{
}