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


static const bson_visitor_t gLoadsVisitors = {
   .visit_double = cbson_loads_visit_double,
   .visit_utf8 = cbson_loads_visit_utf8,
   .visit_array = cbson_loads_visit_array,
   .visit_bool = cbson_loads_visit_bool,
   .visit_int64 = cbson_loads_visit_int64;
};

