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
  m_cursor = _mongoc_cursor_new(client, db_and_collection, flags, skip, limit, batch_size,
                               is_command, query, fields, read_prefs);
}

MongocCursor::~MongocCursor() {
  if (m_cursor != nullptr) {
    mongoc_cursor_destroy (m_cursor);
  }
}

} // namespace HPHP
