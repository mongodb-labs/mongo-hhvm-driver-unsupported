#include "mongo_common.h"
#include <string>

namespace HPHP {

////////MongocClient

////////////////////////////////////////////////////////////////////////////////

Resource get_client_resource(Object obj) {
  auto res = obj->o_realProp(s_mongoc_client, ObjectData::RealPropUnchecked, s_mongoclient);

  if (!res || !res->isResource()) {
    return null_resource;
  }

  return res->toResource();
}

MongocClient *get_client(Object obj) {
  auto res = get_client_resource(obj);

  return res.getTyped<MongocClient>(true, false);
}

MongocClient *MongocClient::GetPersistent(const String& uri) {
  return GetCachedImpl("mongo::persistent_clients", uri);
}

void MongocClient::SetPersistent(const String& uri, MongocClient *client) {
  SetCachedImpl("mongo::persistent_clients", uri, client);
}

MongocClient *MongocClient::GetCachedImpl(const char *name, const String& uri) {
  return dynamic_cast<MongocClient*>(g_persistentResources->get(name, uri.data()));
}

void MongocClient::SetCachedImpl(const char *name, const String& uri, MongocClient *client) {
  g_persistentResources->set(name, uri.data(), client);
}

MongocClient::MongocClient(const String &uri) {
  m_client = mongoc_client_new(uri.c_str());
}

MongocClient::~MongocClient() {
  if (m_client != nullptr) {
    mongoc_client_destroy(m_client);
  }
}

////////MongocCursor

////////////////////////////////////////////////////////////////////////////////

Resource get_cursor_resource(Object obj) {
  auto res = obj->o_realProp(s_mongoc_cursor, ObjectData::RealPropUnchecked, s_mongocursor);

  if (!res || !res->isResource()) {
    return null_resource;
  }

  return res->toResource();
}

MongocCursor *get_cursor(Object obj) {
  auto res = get_cursor_resource(obj);

  return res.getTyped<MongocCursor>(true, false);
}

MongocCursor::MongocCursor(mongoc_client_t           *client,
                const char                *db_and_collection,
                mongoc_query_flags_t       flags,
                uint32_t                   skip,
                uint32_t                   limit,
                uint32_t                   batch_size,
                const bson_t              *query,
                const bson_t              *fields,
                const mongoc_read_prefs_t *read_prefs) {
  std::string db_name;
  std::string collection_name;
  
  std::string *db_and_collection_str = new std::string(db_and_collection);

  //namespace format: db.collection
  size_t dot_pos;
  dot_pos = db_and_collection_str->find_first_of( ".", 0 );
  db_name = db_and_collection_str->substr( 0, dot_pos );
  collection_name = db_and_collection_str->substr( dot_pos+1, std::string::npos );

  mongoc_collection_t *collection;
  
  collection = mongoc_client_get_collection (client, db_name.c_str(), collection_name.c_str());
  m_cursor = mongoc_collection_find (collection,
                                    flags,
                                    skip,
                                    limit,
                                    batch_size,
                                    query,
                                    fields,
                                    read_prefs);
}

MongocCursor::~MongocCursor() {
  if (m_cursor != nullptr) {
    mongoc_cursor_destroy (m_cursor);
  }
}

} // namespace HPHP
