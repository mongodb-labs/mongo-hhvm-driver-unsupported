#include <bson.h>
#include <stdio.h>

int main()
{
  bson_t b[1];

  bson_init( b );
  bson_append_int32( b, "count", 5, 1001);
  printf("number of keys is %d\n", bson_count_keys(b));

  bson_destroy( b );
}
