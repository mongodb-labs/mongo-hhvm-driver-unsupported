#include <iostream>
#include "ext_mongo.h"
#include "bson_decode.h"
#include "contrib/encode.h"

namespace HPHP {

////////////////////////////////////////////////////////////////////////////////
// class MongoCursor

static Array HHVM_METHOD(MongoCursor, current) {
  mongoc_cursor_t *cursor = get_cursor(this_)->get();
  const bson_t *doc;

  doc = mongoc_cursor_current(cursor);
  if (doc) {
    auto ret = cbson_loads(doc); //TODO: We should return the translated PHP Array here  
    return ret;   
  } else {
    return Array::Create(); //Empty array means no valid document left
  }
}

static bool HHVM_METHOD(MongoCursor, hasNext) {
  bson_error_t error;
  mongoc_cursor_t *cursor = get_cursor(this_)->get();

  bool ret = mongoc_cursor_more(cursor);
  if (!mongoc_cursor_error (cursor, &error)) {
    return ret;
  } else {
    throw FatalErrorException(error.message);
  }
}

static void HHVM_METHOD(MongoCursor, next) {
  const bson_t *doc;
  mongoc_cursor_t *cursor = get_cursor(this_)->get();
  // if (!mongoc_cursor_next (cursor, &doc)) {
  //   if (mongoc_cursor_error (cursor, &error)) {
  //     throw FatalErrorException(error.message);
  //   }
  // }
  mongoc_cursor_next (cursor, &doc);   //Note: error would be catched by valid()
}

static void HHVM_METHOD(MongoCursor, reset) {
  if (get_cursor(this_)) {
    get_cursor(this_)->~MongocCursor();
  }
}

static void HHVM_METHOD(MongoCursor, rewind) {
  HHVM_MN(MongoCursor, reset)(this_);

  mongoc_collection_t *collection;
  //TODO: need to test with null value
  auto connection = this_->o_realProp("connection", ObjectData::RealPropUnchecked, "MongoCursor")->toObject();
  auto ns = this_->o_realProp("ns", ObjectData::RealPropUnchecked, "MongoCursor")->toString();
  auto query = this_->o_realProp("query", ObjectData::RealPropUnchecked, "MongoCursor")->toArray();
  bson_t query_bs;
  query_bs = encodeToBSON(query);

  // TODO commands
  auto db = this_->o_realProp("db", ObjectData::RealPropUnchecked, "MongoCollection")->toObject();
  auto client = db->o_realProp("client", ObjectData::RealPropUnchecked, "MongoDB")->toObject();
  String db_name = db->o_realProp("db_name", ObjectData::RealPropUnchecked, "MongoDB")->toString();
  String collection_name = this_->o_realProp("name", ObjectData::RealPropUnchecked, "MongoCollection")->toString();
  
  
  collection = mongoc_client_get_collection (get_client(client)->get(), db_name.c_str(), collection_name.c_str());
  m_cursor = mongoc_collection_find (collection,
                                    flags,
                                    skip,
                                    limit,
                                    batch_size,
                                    query,
                                    fields,
                                    read_prefs);
  
  /*
  bson_init(&query_bs);
  if (!query->empty()) {
    //Currently only supports "name" query
    bson_append_utf8(&query_bs, "name", 4, query[String("name")].toString().c_str(), query[String("name")].toString().length());
    */

  }
  

//Parameters and their types:
//static void HHVM_METHOD(MongoCursor, __construct, const Object& connection, const String& ns, const Array& query, const Array& fields)

MongocCursor(mongoc_client_t           *client,
                const char                *db_and_collection,
                mongoc_query_flags_t       flags,
                uint32_t                   skip,
                uint32_t                   limit,
                uint32_t                   batch_size,
                bool                       is_command,
                const bson_t              *query,
                const bson_t              *fields,
                const mongoc_read_prefs_t *read_prefs);
                
  MongocCursor *cursor = new MongocCursor(get_client(connection)->get(), ns.c_str(), MONGOC_QUERY_NONE, 0, 0, 0, false, &query_bs, NULL, NULL);
  //std::cout << "Got past cursor construction with" << ns.c_str() << std::endl;

/* fields needed:
  
  private $flags = [];  //TODO: implement this
  private $skip = 0;
  private $limit = 0;
  private $batchSize = 100;
  private $fields = [];
  private $read_preference = [];

  */

  bson_t fields_bs;
  mongoc_read_prefs_t *read_prefs;
  bson_t read_prefs_tags_bs;

  auto flags_array = this_->o_realProp("flags", ObjectData::RealPropUnchecked, "MongoCursor")->toArray();
  mongoc_query_flags_t flags = MONGOC_QUERY_NONE;

  //if (flags_array->exists(0)) { flags |= MONGOC_QUERY_NONE;}
  // if (flags_array->exists(1)) { flags = (flags | MONGOC_QUERY_TAILABLE_CURSOR);}
  // if (flags_array->exists(2)) { flags = (flags | MONGOC_QUERY_SLAVE_OK);}
  // if (flags_array->exists(3)) { flags = (flags | MONGOC_QUERY_OPLOG_REPLAY);}
  // if (flags_array->exists(4)) { flags = (flags | MONGOC_QUERY_NO_CURSOR_TIMEOUT);}
  // if (flags_array->exists(5)) { flags = (flags | MONGOC_QUERY_AWAIT_DATA);}
  // if (flags_array->exists(6)) { flags = (flags | MONGOC_QUERY_EXHAUST);}
  // if (flags_array->exists(7)) { flags = (flags | MONGOC_QUERY_PARTIAL);}

  uint32_t skip = this_->o_realProp("skip", ObjectData::RealPropUnchecked, "MongoCursor")->toInt32();
  uint32_t limit = this_->o_realProp("limit", ObjectData::RealPropUnchecked, "MongoCursor")->toInt32();
  uint32_t batchSize = this_->o_realProp("batchSize", ObjectData::RealPropUnchecked, "MongoCursor")->toInt32();
  auto fields = this_->o_realProp("fields", ObjectData::RealPropUnchecked, "MongoCursor")->toArray();

  auto read_prefs_array = this_->o_realProp("read_preference", ObjectData::RealPropUnchecked, "MongoCursor")->toArray();
  String read_pref_type = read_prefs_array[String("type")].toString();
  Array read_pref_tagsets = read_prefs_array[String("tagsets")].toArray();
  read_prefs_tags_bs = encodeToBSON(read_pref_tagsets);
  /*
  MongoClient::RP_PRIMARY, 
  MongoClient::RP_PRIMARY_PREFERRED, 
  MongoClient::RP_SECONDARY, 
  MongoClient::RP_SECONDARY_PREFERRED, 
  MongoClient::RP_NEAREST
  */
  read_prefs = mongoc_read_prefs_new(MONGOC_READ_PRIMARY);

  if (read_pref_type.equal(String("RP_PRIMARY"))) {
    mongoc_read_prefs_set_mode(read_prefs, MONGOC_READ_PRIMARY);
  } else if (read_pref_type.equal(String("RP_PRIMARY_PREFERRED"))) {
    mongoc_read_prefs_set_mode(read_prefs, MONGOC_READ_PRIMARY_PREFERRED);
  } else if (read_pref_type.equal(String("RP_SECONDARY"))) {
    mongoc_read_prefs_set_mode(read_prefs, MONGOC_READ_SECONDARY);
  } else if (read_pref_type.equal(String("RP_SECONDARY_PREFERRED"))) {
    mongoc_read_prefs_set_mode(read_prefs, MONGOC_READ_SECONDARY_PREFERRED);
  } else if (read_pref_type.equal(String("RP_NEAREST"))) {
    mongoc_read_prefs_set_mode(read_prefs, MONGOC_READ_NEAREST);
  }
  mongoc_read_prefs_set_tags(read_prefs, &read_prefs_tags_bs);
  
  fields_bs = encodeToBSON(fields);   

  MongocCursor *cursor = new MongocCursor(get_client(connection)->get(), ns.c_str(), flags, skip, limit, batchSize, false, &query_bs, &fields_bs, read_prefs);

  this_->o_set(s_mongoc_cursor, cursor, s_mongocursor);
  bson_destroy(&query_bs);
  bson_destroy(&fields_bs);
  bson_destroy(&read_prefs_tags_bs);

  this_->o_set("started_iterating", Variant(true), "MongoCursor");

  HHVM_MN(MongoCursor, next)(this_);
}

static bool HHVM_METHOD(MongoCursor, valid) {
  auto cur = HHVM_MN(MongoCursor, current)(this_);
  return !(cur->empty());
}

////////////////////////////////////////////////////////////////////////////////

void mongoExtension::_initMongoCursorClass() {
    HHVM_ME(MongoCursor, current);
    HHVM_ME(MongoCursor, hasNext);
    HHVM_ME(MongoCursor, next);
    HHVM_ME(MongoCursor, reset);
    HHVM_ME(MongoCursor, rewind);
    HHVM_ME(MongoCursor, valid);
}

} // namespace HPHP
