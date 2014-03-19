#ifndef incl_HPHP_EXT_MONGO_COMMON_H_
#define incl_HPHP_EXT_MONGO_COMMON_H_

#include "hphp/runtime/base/base-includes.h"
#include "mongoc.h"
#include "string.h"

namespace HPHP {

class MongocClient : public SweepableResourceData {
public:
  static MongocClient *GetPersistent(const String& uri);
  static void SetPersistent(const String& uri, MongocClient *client);

private:
  static MongocClient *GetCachedImpl(const char *name, const String& uri);
  static void SetCachedImpl(const char *name, const String& uri, MongocClient *client);

public:
  MongocClient(const String& uri);
  ~MongocClient();

  CLASSNAME_IS("mongoc client")

  // overriding ResourceData
  virtual const String& o_getClassNameHook() const { return classnameof(); }
  virtual bool isInvalid() const { return m_client == nullptr; }

  mongoc_client_t *get() { return m_client;}

private:
  mongoc_client_t *m_client;

};

class MongocCursor : public SweepableResourceData {
public:
  //Reference: https://github.com/mongodb/mongo-c-driver/blob/e6038636bcee5264a264b54afce0b93c39884d97/src/mongoc/mongoc-cursor.c
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
  ~MongocCursor();

  CLASSNAME_IS("mongoc cursor")

  // overriding ResourceData
  virtual const String& o_getClassNameHook() const { return classnameof(); }
  virtual bool isInvalid() const { return m_cursor == nullptr; }

  mongoc_cursor_t *get() { return m_cursor;}

private:
  mongoc_cursor_t *m_cursor;

};

}

#endif // incl_HPHP_EXT_MONGO_COMMON_H_
