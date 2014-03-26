#include <bson.h>
#include "hphp/runtime/base/base-includes.h"

static bool
cbson_loads_visit_int64 (const bson_iter_t *iter,
                         const char*        key,
                         int64_t          value,
                         Array*             *output)
{
  output->add(String(key),value);
  
  return false;
}
