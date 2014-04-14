#include <bson.h>
// #include <stdio.h>
#include "mongo_classes.h"
#include "hphp/runtime/base/base-includes.h"
#include "bson_decode.h"

namespace HPHP {

static bool
cbson_loads_visit_int32 (const bson_iter_t *iter,
                         const char         *key,
                         int32_t           v_int32,
                         Array             *output)
{
  output->add(String(key), v_int32);
  return false;
}

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

static bool
cbson_loads_visit_null (const bson_iter_t *iter,
                        const char        *key,                          
                        Array             *output)
{
  output->add(String(key), Variant());
  return false;
} 

// TODO: not sure how to instantiate the classes
// that Marco created
static bool
cbson_loads_visit_oid (const bson_iter_t *iter,
                       const char        *key,
                       const bson_oid_t  *oid,
                       Array             *output)
{
  char id[25];
  bson_oid_to_string(oid, id);
  
  bson_to_object(iter, output, &s_MongoId, 
    make_packed_array(String(id)));
  return false;
}

static bool
cbson_loads_visit_date_time (const bson_iter_t *iter,
                             const char        *key,
                             int64_t       msec_since_epoch,
                             Array              *output)
{
  // Renaming for convenience
  int64_t msec = msec_since_epoch;
  bson_to_object(iter, output, &_MongoDate,
    make_packed_array(msec / 1000, (msec % 1000) * 1000));

  return false;
}

static bool
cbson_loads_visit_document (const bson_iter_t *iter,
                            const char        *key,
                            const bson_t      *v_document,
                            Array              *data)
{
  bson_iter_t child;
  Array ret = data;
  Array obj;

  bson_return_val_if_fail(iter, true);
  bson_return_val_if_fail(key, true);
  bson_return_val_if_fail(v_document, true);

  if (bson_iter_init(&child, v_document))
  {
    obj = Array();
    if (!bson_iter_visit_all(&child, &gLoadsVisitors, &obj))
    {
      ret->add(String(key, obj));
    }
  }

  return false;
}

static bool
cbson_loads_visit_array (const bson_iter_t *iter,
                         const char        *key,
                         const bson_t      *v_array,
                         Array              *data)
{
  bson_iter_t child;
  Array ret = data;
  Array obj;

  bson_return_val_if_fail(iter, true);
  bson_return_val_if_fail(key, true);
  bson_return_val_if_fail(v_array, true);

  if (bson_iter_init(&child, v_array))
  {
    obj = Array();
    if (!bson_iter_visit_all(&child, &gLoadsVisitors, &obj))
    {
      ret->add(String(key, obj));
    }
  }
  return false;
}

static bool
cbson_loads_visit_regex (const bson_iter_t *iter,
                         const char        *key,
                         const char        *regex,
                         const char        *options,
                         Array              *output)
{
  String regex_string = "/" + String(regex) + "/" + String(options);

  bson_to_object(iter, output, &_MongoRegex,
    make_packed_array(regex_string));

  return false;
}

// Not implemented in cbson.c
static bool
cbson_loads_visit_timestamp (const bson_iter_t *iter,
                             const char        *key,
                             uint32_t          *timestamp,
                             uint32_t          *increment,
                             Array             *output)
{
  bson_to_object(iter, output, &_MongoTimestamp,
    make_packed_array((int64_t)timestamp, (int64_t)increment));
  
  return false;
}


extern "C": 
{
  static const bson_visitor_t gLoadsVisitors = 
    {
       .visit_double = cbson_loads_visit_double,
       .visit_int32 = cbson_loads_visit_int32,
       .visit_utf8 = cbson_loads_visit_utf8,
       .visit_bool = cbson_loads_visit_bool,
       .visit_int64 = cbson_loads_visit_int64,
       .visit_null = cbson_loads_visit_null,
       .visit_oid = cbson_loads_visit_oid,
       .visit_array = cbson_loads_visit_array,
       .visit_document = cbson_loads_visit_document,
       .visit_date_time = cbson_loads_date_time,
       .visit_regex = cbson_loads_visit_regex,
       .visit_timestamp = cbson_loads_visit_timestamp,
    };
}


static Array
cbson_loads (bson_t * bson) 
{
  const bson_t * b;
  bson_iter_t iter;
  bool eof;

  Array ret = Array();

  if (!bson_iter_init(&iter, bson))
  {

    printf("Failed to initialize bson iterator. \n");
    return NULL;
  }
  bson_iter_visit_all(&iter, &gLoadsVisitors, &ret);

  return ret;
}

static ObjectData * 
create_object(const StaticString * className, Array params)
{
  Class * cls = Unit::loadClass(className -> get());
  ObjectData * obj = ObjectData::newInstance(cls);
  obj->incRefCount();

  g_context->invokeFunc(
    &ret,
    cls->getCtor(),
    params,
    obj
  );
}

static void 
bson_to_object(bson_iter_t * iter, 
                Array * output,
                const StaticString * className,
                Array params)
{
  output->add( String(bson_iter_key(iter)),
               create_object(className, params));
}

static Array
cbson_loads_from_string (const String& bson) 
{
  bson_reader_t * reader;
  const bson_t * obj;
  bool reached_eof;

  Array output = Array();

  reader = bson_reader_new_from_data((uint8_t *)bson.c_str(), bson.size());
  obj = bson_reader_read(reader, &reached_eof);

  cbson_loads(obj, &output);
  bson_reader_destroy(reader);

  return output;
}
// Namespace
}

// Function used for testing
// int main(int argc, char **argv)
// {
//   bson_t b[1];
//   bson_init( b );
//   BSON_APPEND_INT32( b, "int32", 1001);
//   BSON_APPEND_INT64( b, "int64", 999999);
//   BSON_APPEND_UTF8(b, "string", "test string");
//   BSON_APPEND_BOOL(b, "boolean", true);
//   printf("number of keys is %d\n", bson_count_keys(b));
//   cbson_loads(b);
//   bson_destroy( b );
// }
