#include "ext_mongo.h"

namespace HPHP {

static mongoc_collection_t *get_collection(Object obj) {
  mongoc_collection_t *collection;

  auto db = obj->o_realProp("db", ObjectData::RealPropUnchecked, "MongoCollection")->toObject();
  auto client = db->o_realProp("client", ObjectData::RealPropUnchecked, "MongoDB")->toObject();
  String db_name = db->o_realProp("db_name", ObjectData::RealPropUnchecked, "MongoDB")->toString();
  String collection_name = obj->o_realProp("name", ObjectData::RealPropUnchecked, "MongoCollection")->toString();

  collection = mongoc_client_get_collection (get_client(client)->get(), db_name.c_str(), collection_name.c_str());
  return collection;
}

////////////////////////////////////////////////////////////////////////////////
// class MongoCollection

//public function createDBRef(mixed $document_or_id): array;
static Array HHVM_METHOD(MongoCollection, createDBRef, Variant document_or_id) {
  throw NotImplementedException("Not Implemented");
}

//public function deleteIndex(mixed $keys): array;
static Array HHVM_METHOD(MongoCollection, deleteIndex, Variant keys) {
  throw NotImplementedException("Not Implemented");
}

//public function deleteIndexes(): array;
static Array HHVM_METHOD(MongoCollection, deleteIndexes) {
  throw NotImplementedException("Not Implemented");
}

//public function distinct(string $key, array $query): array;
static Array HHVM_METHOD(MongoCollection, distinct, const String& key, Array query) {
  throw NotImplementedException("Not Implemented");
}

//public function drop(): array;
static Array HHVM_METHOD(MongoCollection, drop) {
  mongoc_collection_t *collection;
  bson_error_t error;

  collection = get_collection(this_);

  bool result = mongoc_collection_drop(collection, &error);

  auto ret = Array::Create("return", result);
  return ret;
  /*
  mongoc_collection_drop (mongoc_collection_t           *collection,
                          bson_error_t                  *error);
  */
}

//public function ensureIndex(mixed $key, array $options = array()): bool;
static bool HHVM_METHOD(MongoCollection, ensureIndex, Variant key, Array options) {
  throw NotImplementedException("Not Implemented");
}

/*
  public function findAndModify(array $query,
                                array $update,
                                array $fields,
                                array $options): array;
*/
static Array HHVM_METHOD(MongoCollection, findAndModify, Array query, Array update, Array fields, Array options) {
  mongoc_collection_t *collection; 
  bson_t query = BSON_INITIALIZER;
  bson_t *update;
  bson_t *fields;
  bson_t reply;
  bson_error_t error; 

  collection = get_collection(this_); 

  //TODO: 
  //set query, update and fields

  bool ret = mongoc_collection_find_and_modify(collection, &query, NULL, update, fields, false, false, true, &reply, &error); 
  

  bson_destroy (&reply);
  bson_destroy (update);
  bson_destroy(fields);
  bson_destroy(&query);

  return ret; 

}

//public function getDBRef(array $ref): array;
static Array HHVM_METHOD(MongoCollection, getDBRef, Array ref) {
  throw NotImplementedException("Not Implemented");
}

//public function getIndexInfo(): array;
static Array HHVM_METHOD(MongoCollection, getIndexInfo) {
  throw NotImplementedException("Not Implemented");
}

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
//public function insert(mixed $a, array $options = array()): mixed;
static Variant HHVM_METHOD(MongoCollection, insert, Variant a, Array options) {
  mongoc_collection_t *collection;
  bson_t doc;
  bson_error_t error;
  bson_oid_t oid;

  collection = get_collection(this_);

  Array doc_array = a.toArray();
  bson_init(&doc);
  bson_oid_init_from_string(&oid, doc_array[String("_id")].toString().c_str());
  bson_append_oid(&doc, "_id", 3, &oid);
  //Supporting only "name" key
  bson_append_utf8(&doc, "name", 4, doc_array[String("name")].toString().c_str(), doc_array[String("name")].toString().length());

  bool ret = mongoc_collection_insert(collection, MONGOC_INSERT_NONE, &doc, NULL, &error);

  mongoc_collection_destroy (collection);
  bson_destroy(&doc);

  return ret;
  /*
  bool mongoc_collection_insert (mongoc_collection_t           *collection,
                                mongoc_insert_flags_t          flags,
                                const bson_t                  *document,
                                const mongoc_write_concern_t  *write_concern,
                                bson_error_t                  *error);
  */

}

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
//public function remove(array $criteria = array(), array $options = array()): mixed;
static Variant HHVM_METHOD(MongoCollection, remove, Array criteria, Array options) {
  mongoc_collection_t *collection;
  bson_t criteria_b;
  bson_error_t error;

  collection = get_collection(this_);

  bson_init(&criteria_b);
  //Supporting only "name" key
  bson_append_utf8(&criteria_b, "name", 4, criteria[String("name")].toString().c_str(), criteria[String("name")].toString().length());

  bool ret = mongoc_collection_delete(collection, MONGOC_DELETE_NONE, &criteria_b, NULL, &error);

  mongoc_collection_destroy (collection);
  bson_destroy(&criteria_b);

  return ret;
  /*
  bool mongoc_collection_delete (mongoc_collection_t           *collection,
                                mongoc_delete_flags_t          flags,
                                const bson_t                  *selector,
                                const mongoc_write_concern_t  *write_concern,
                                bson_error_t                  *error);
  */
}

//public function save(mixed $a, array $options = array()): mixed;
static Variant HHVM_METHOD(MongoCollection, save, Variant a, Array options) {
  mongoc_collection_t *collection;
  bson_t doc;
  bson_error_t error;
  bson_oid_t oid;

  collection = get_collection(this_);

  Array doc_array = a.toArray();
  bson_init(&doc);
  bson_oid_init_from_string(&oid, doc_array[String("_id")].toString().c_str());
  bson_append_oid(&doc, "_id", 3, &oid);
  //Supporting only "name" key
  bson_append_utf8(&doc, "name", 4, doc_array[String("name")].toString().c_str(), doc_array[String("name")].toString().length());

  bool ret = mongoc_collection_save(collection, &doc, NULL, &error);

  mongoc_collection_destroy (collection);
  bson_destroy(&doc);

  return ret;

  /*
  bool
mongoc_collection_save (mongoc_collection_t          *collection,
                        const bson_t                 *document,
                        const mongoc_write_concern_t *write_concern,
                        bson_error_t                 *error)
  */
}

//static protected function toIndexString(mixed $keys): string;
static String HHVM_STATIC_METHOD(MongoCollection, toIndexString, Variant keys)

/*
char *
mongoc_collection_keys_to_index_string (const bson_t *keys)
*/
}

/*
public function update(array $criteria,
                         array $new_object,
                         array $options = array()): mixed;
*/
static Variant HHVM_METHOD(MongoCollection, update, Array criteria, Array new_object, Array options) { 
  mongoc_collection_t *collection; 
  bson_t selector; //selector is the criteria (which document to update)
  bson_t update;  //update is the new_object containing the new data 
  bson_oid_t oid;
  bson_error_t error;

  collection = get_collection(this_); 

  //Read oid and name from criteria array 
  bson_init(&selector); 
  bson_oid_init_from_string(&oid, criteria[String("_id")].toString().c_str()); 
  bson_append_oid(&selector, "_id", 3, &oid); 
  bson_append_utf8(&selector, "name", 4, criteria[String("name")].toString().c_str(), criteria[String("name")].toString().length()); 

  //Convert new_object to bson 
  //Hard coded test for now 
  bson_init(&update); 
  BSON_APPEND_INT32 (&u, "abcd", 1);
  BSON_APPEND_INT32 (&u, "$hi", 1);

  bool ret = mongoc_collection_update(collection, MONGOC_UPDATE_NONE, &selector, &update, NULL, &error);

  mongoc_collection_destroy (collection);

  bson_destroy(&update);
  bson_destroy(&selector); 

  return ret; 

/*
bool
mongoc_collection_update (mongoc_collection_t          *collection,
                          mongoc_update_flags_t         flags,
                          const bson_t                 *selector,
                          const bson_t                 *update,
                          const mongoc_write_concern_t *write_concern,
                          bson_error_t                 *error)
*/
}

//public function validate(bool $scan_data = false): array;
static Array HHVM_METHOD(MongoCollection, validate, bool scan_data) {
  throw NotImplementedException("Not Implemented");
}

////////////////////////////////////////////////////////////////////////////////

void mongoExtension::_initMongoCollectionClass() {
    HHVM_ME(MongoCollection, createDBRef);
    HHVM_ME(MongoCollection, deleteIndex);
    HHVM_ME(MongoCollection, deleteIndexes);
    HHVM_ME(MongoCollection, distinct);
    HHVM_ME(MongoCollection, drop);
    HHVM_ME(MongoCollection, ensureIndex);
    HHVM_ME(MongoCollection, findAndModify);
    HHVM_ME(MongoCollection, getDBRef);
    HHVM_ME(MongoCollection, getIndexInfo);
    HHVM_ME(MongoCollection, insert);
    HHVM_ME(MongoCollection, remove);
    HHVM_ME(MongoCollection, save);
    HHVM_STATIC_ME(MongoCollection, toIndexString);
    HHVM_ME(MongoCollection, update);
    HHVM_ME(MongoCollection, validate);
}

} // namespace HPHP
