#ifndef incl_HPHP_EXT_MONGO_COMMON_H_
#define incl_HPHP_EXT_MONGO_COMMON_H_

#include "hphp/runtime/base/base-includes.h"
#include "mongoc.h"

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

}

#endif // incl_HPHP_EXT_MONGO_COMMON_H_
