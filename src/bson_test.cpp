#include <bson.h>
#include <stdio.h>

int main()
{
  bson_t b[1];
  bson_init( b );
  bson_append_int32( b, "int32", 5,  1001);
  bson_append_int64( b, "int64", 5, 999999);
  bson_append_utf8( b, "string", 6, "test string", 11);
  bson_append_bool(b, "boolean", 7, true);
  printf("number of keys is %d\n", bson_count_keys(b));
  // Array arr = HPHP::cbson_loads(b);
  bson_destroy( b );
}
