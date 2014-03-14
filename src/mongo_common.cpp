#include "mongo_common.h"

namespace HPHP {

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

} // namespace HPHP
