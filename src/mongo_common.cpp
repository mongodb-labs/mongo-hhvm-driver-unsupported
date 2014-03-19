#include "mongo_common.h"

namespace HPHP {

////////MongocClient

MongocClient *MongocClient::GetPersistent(const String& uri) {
  return GetCachedImpl("mongo::persistent_clients", uri);
}

void MongocClient::SetPersistent(const String& uri, MongocClient *client) {
  SetCachedImpl("mongo::persistent_clients", uri, client);
}

MongocClient *MongocClient::GetCachedImpl(const char *name, const String& uri) {
  return dynamic_cast<MongocClient*>(g_persistentObjects->get(name, uri.data()));
}

void MongocClient::SetCachedImpl(const char *name, const String& uri, MongocClient *client) {
  g_persistentObjects->set(name, uri.data(), client);
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

MongocCursor::MongocCursor(mongoc_client_t           *client,
                const char                *db_and_collection,
                mongoc_query_flags_t       flags,
                uint32_t                   skip,
                uint32_t                   limit,
                uint32_t                   batch_size,
                bool                       is_command,
                const bson_t              *query,
                const bson_t              *fields,
                const mongoc_read_prefs_t *read_prefs) {
  //m_cursor = _mongoc_cursor_new(client, db_and_collection, flags, skip, limit, batch_size,
  //                            is_command, query, fields, read_prefs);
  const char *db_name;
  const char *collection_name;
  const char dot[] = ".";
  mongoc_collection_t *collection;

  char *db_and_collection_mutable = strdupa(db_and_collection);
  db_name = strsep(&db_and_collection_mutable, dot);
  collection_name = db_and_collection_mutable;
  //collection_name = strchr(db_and_collection, '.');
  //collection_name = collection_name + 1;
  
  //See https://github.com/mongodb/mongo-php-driver/blob/6316dc9d36a212a0ff983543530dce76a3f2b91c/cursor_shared.c#L440 for checking valid namespace
  collection = mongoc_client_get_collection (client, db_name, collection_name);
  m_cursor = mongoc_collection_find (collection,
                                    flags,
                                    skip,
                                   	limit,
                                    batch_size,
                                    query,
                                    fields,  /* Fields, NULL for all. */
                                    read_prefs); /* Read Prefs, NULL for default */
  mongoc_collection_destroy (collection);
}

MongocCursor::~MongocCursor() {
  if (m_cursor != nullptr) {
    mongoc_cursor_destroy (m_cursor);
  }
}

} // namespace HPHP
