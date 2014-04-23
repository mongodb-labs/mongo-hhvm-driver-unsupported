/* Copyright (c) 2014 Di Máximo Cuadros
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
#include "hphp/runtime/base/base-includes.h"
#include <bson.h>

namespace HPHP {
bool arrayIsDocument(const Array& arr);
void fillBSONWithArray(const Array& value, bson_t* bson);
void stringToBSON(const String& value, const char* key, bson_t* bson);
void arrayToBSON(const Array& value, const char* key, bson_t* bson);
void objectToBSON(const Object& value, const char* key, bson_t* bson);
void variantToBSON(const Variant& value, const char* key, bson_t* bson);
void int64ToBSON(const int64_t value, const char* key, bson_t* bson);
void boolToBSON(const bool value, const char* key, bson_t* bson);
void nullToBSON(const char* key, bson_t* bson);
void doubleToBSON(const double value,const char* key, bson_t* bson);
void mongoDateToBSON(const Object& value, const char* key, bson_t* bson);
void mongoIdToBSON(const Object& value, const char* key, bson_t* bson);
void mongoRegexToBSON(const Object& value, const char* key, bson_t* bson);
void mongoTimestampToBSON(const Object& value, const char* key, bson_t* bson);
void mongoCodeToBSON(const Object& value, const char* key, bson_t* bson);
void mongoBinDataToBSON(const Object& value, const char* key, bson_t* bson);
void mongoInt32ToBSON(const Object& value, const char* key, bson_t* bson);
void mongoInt64ToBSON(const Object& value, const char* key, bson_t* bson);
void mongoMinKeyToBSON(const char* key, bson_t* bson);
void mongoMaxKeyToBSON(const char* key, bson_t* bson);
}
