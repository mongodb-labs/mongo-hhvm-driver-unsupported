#include <bson.h>
#include <stdio.h>
#include "hphp/runtime/base/base-includes.h"

static bool
cbson_loads_visit_int64 (const bson_iter_t *iter,
                         const char*        key,
                         int64_t          v_int64,
                         Array             *output)
{
  output->add(String(key),v_int64);
  
  return false;
}

static bool
cbson_loads_visit_bool (const bson_iter_t *iter,
                        const char        *key,
                        bool        v_bool,
                        Array       *output)
{
   output->add(String(key), v_bool);
   return false;
}


static bool
cbson_loads_visit_array (const bson_iter_t *iter,
                        const char        *key,
                        const bson_t     v_array,
                        Array           *output)
{
   return false;
}

static bool
cbson_loads_visit_double (const bson_iter_t *iter,
                          const char        *key,
                          double             v_double,
                          Array             *output)
{
   output->add(String(key),v_double);
   return false;
}

static bool
cbson_loads_visit_utf8 (const bson_iter_t *iter,
                        const char        *key,
                        size_t             v_utf8_len,
                        const char        *v_utf8,
                        Array             *output)
{
   output->add(String(key),v_utf8); 
   return false;
}


static const bson_visitor_t gLoadsVisitors = {
   .visit_double = cbson_loads_visit_double,
   .visit_utf8 = cbson_loads_visit_utf8,
   .visit_array = cbson_loads_visit_array,
   .visit_bool = cbson_loads_visit_bool,
   .visit_int64 = cbson_loads_visit_int64;
};

static Array * 
cbson_loads (bson_t * bson) 
{
  bson_reader_t * reader;
  const bson_t * b;
  bson_iter_t iter;
  bool eof;

  Array ret = Array();

  reader = bson_reader_new_from_data((uint8_t *)bson.c_str(), bson.size());

  if (!(b = bson_reader_read(reader, &eof))
  {
    std::cout << "Buffer contained invalid BSON." << endl;
  }

  do {
    if (!bson_iter_init(&iter, b))
    {
      bson_reader_destroy(reader);
      std::cout << "Failed to initiate iterator." << endl;
    }
    bson_iter_visit_all(&iter, &gLoadsVisitors, &ret); 
  } while ((b = bson_reader_read(reader, &eof)));

  bson_reader_destroy(reader);

  if (!eof) {
    std::cout << "Buffer contained invalid BSON." << endl;
    return;
  }

  return ret;
}
