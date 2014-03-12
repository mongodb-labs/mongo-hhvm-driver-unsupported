<?hh

/**
 * The class can be used to save 32-bit integers to the database on a 64-bit
 * system.
 */
class MongoInt32 {
  /**
   * Creates a new 32-bit integer.
   *
   * @param string $value - value    A number.
   *
   * @return  - Returns a new integer.
   */
  <<__Native>>
  public function __construct(string $value): void;

  /**
   * Returns the string representation of this 32-bit integer.
   *
   * @return string - Returns the string representation of this integer.
   */
  <<__Native>>
  public function __toString(): string;

}