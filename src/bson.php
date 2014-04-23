<?hh

class Encoding {

	<<__Native>>
	public static function bson_decode(string $bson): array;

  <<__Native>>
  public static function bson_encode(mixed $mixture): string;
}
