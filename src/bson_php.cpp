#include <bson.h>
#include "hphp/runtime/base/base-includes.h"

static bool
cbson_loads_visit_int64 (const bson_iter_t *iter,
                         const char*        key,
                         int64_t          v_int64,
                         void             *output)
{
  ((Array *)output)->add(String(key),v_int64);
  
  return false;
}

static bool
cbson_loads_visit_bool (const bson_iter_t *iter,
                        const char        *key,
                        bool        v_bool,
                        void       *output)
{
   ((Array *)output)->add(String(key), v_bool);
   return false;
}


static bool
cbson_loads_visit_array (const bson_iter_t *iter,
                        const char        *key,
                        const bson_t     v_array,
                        void           *output)
{
   return false;
}
static const bson_visitor_t gLoadsVisitors = {
   .visit_double = cbson_loads_visit_double,
   .visit_utf8 = cbson_loads_visit_utf8,
   .visit_array = cbson_loads_visit_array,
   .visit_bool = cbson_loads_visit_bool,
   .visit_int64 = cbson_loads_visit_int64;
};

