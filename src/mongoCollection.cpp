#include "ext_mongo.h"
#include "contrib/encode.h"

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

  collection = get_collection(this_);

  Array doc_array = a.toArray();
  doc = encodeToBSON(doc_array);
  // bson_init(&doc);
  // bson_oid_init_from_string(&oid, doc_array[String("_id")].toString().c_str());
  // bson_append_oid(&doc, "_id", 3, &oid);
  // //Supporting only "name" key
  // bson_append_utf8(&doc, "name", 4, doc_array[String("name")].toString().c_str(), doc_array[String("name")].toString().length());

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

  criteria_b = encodeToBSON(criteria);
  
  //Supporting only "name" key
  //bson_append_utf8(&criteria_b, "name", 4, criteria[String("name")].toString().c_str(), criteria[String("name")].toString().length());

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


/*
public function update(array $criteria,
                         array $new_object,
                         array $options = array()): mixed;
*/
static Variant HHVM_METHOD(MongoCollection, update, Array criteria, Array new_object, Array options) { 
  mongoc_collection_t *collection; 
  bson_t selector; //selector is the criteria (which document to update)
  bson_t update;  //update is the new_object containing the new data 
  bson_error_t error;

  collection = get_collection(this_); 

  selector = encodeToBSON(criteria);
  update = encodeToBSON(new_object);

  //Read oid and name from criteria array 
  // bson_init(&selector); 
  // bson_oid_init_from_string(&oid, criteria[String("_id")].toString().c_str()); 
  // bson_append_oid(&selector, "_id", 3, &oid); 
  // bson_append_utf8(&selector, "name", 4, criteria[String("name")].toString().c_str(), criteria[String("name")].toString().length()); 

  //Convert new_object to bson 
  //Hard coded test for now 
  // bson_init(&update); 
  // BSON_APPEND_INT32 (&update, "abcd", 1);
  // BSON_APPEND_INT32 (&update, "$hi", 1);

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


////////////////////////////////////////////////////////////////////////////////

void mongoExtension::_initMongoCollectionClass() {
    HHVM_ME(MongoCollection, insert);
    HHVM_ME(MongoCollection, remove);
    HHVM_ME(MongoCollection, update);
}

} // namespace HPHP
