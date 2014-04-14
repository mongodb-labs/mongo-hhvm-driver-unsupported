#include "hphp/runtime/base/base-includes.h"
#include <bson.h>

namespace HPHP {
  
  Array cbson_loads_from_string(const String& bson);
  
}