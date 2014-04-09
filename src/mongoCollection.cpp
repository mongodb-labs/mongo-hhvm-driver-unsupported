#include "ext_mongo.h"

namespace HPHP {

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
  throw NotImplementedException("Not Implemented");
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
  throw NotImplementedException("Not Implemented");
}

//public function getDBRef(array $ref): array;
static Array HHVM_METHOD(MongoCollection, getDBRef, Array ref) {
  throw NotImplementedException("Not Implemented");
}

//public function getIndexInfo(): array;
static Array HHVM_METHOD(MongoCollection, getIndexInfo) {
  throw NotImplementedException("Not Implemented");
}

//public function insert(mixed $a, array $options = array()): mixed;
static Variant HHVM_METHOD(MongoCollection, insert, Variant a, Array options) {
  throw NotImplementedException("Not Implemented");
}

//public function remove(array $criteria = array(), array $options = array()): mixed;
static Variant HHVM_METHOD(MongoCollection, remove, Array criteria, Array options) {
  throw NotImplementedException("Not Implemented");
}

//public function save(mixed $a, array $options = array()): mixed;
static Variant HHVM_METHOD(MongoCollection, save, Variant a, Array options) {
  throw NotImplementedException("Not Implemented");
}

//static protected function toIndexString(mixed $keys): string;
static String HHVM_STATIC_METHOD(MongoCollection, toIndexString, Variant keys) {
  throw NotImplementedException("Not Implemented");
}

/*
public function update(array $criteria,
                         array $new_object,
                         array $options = array()): mixed;
*/
static Variant HHVM_METHOD(MongoCollection, update, Array criteria, Array new_object, Array options) {
  throw NotImplementedException("Not Implemented");
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
