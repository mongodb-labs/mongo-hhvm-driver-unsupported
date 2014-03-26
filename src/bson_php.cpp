#include <bson.h>
#include "hphp/runtime/base/base-includes.h"

static bool
cbson_loads_visit_int64 (const bson_iter_t *iter,
                         Array*             *output)
{
  output->add(
    String(bson_iter_key(iter)),
    bson_iter_int64(iter)
  )
}
