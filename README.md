# MongoDB driver for HHVM

This is an implementation of the
[MongoDB PHP driver](https://github.com/mongodb/mongo-php-driver) for
[HHVM](https://github.com/facebook/hhvm). It is not feature-complete and should
be considered experimental.

## Dependencies

Compiling this extension requires the following libraries:

 * HHVM (>=3.0.0) must be compiled from source, since the binary distributions
   of HHVM do not include necessary development headers. Instructions for
   compiling HHVM may be found
   [here](https://github.com/facebook/hhvm/wiki#building-hhvm).

 * libmongoc (>=0.94.0) and its corresponding libbson dependency must be
   installed as a system library. Instructions for installing libmongoc may be
   found
   [here](https://github.com/mongodb/mongo-c-driver#fetch-sources-and-build).

## Building and installation

Ensure that the `HPHP_HOME` environment variable is set to the HHVM project
directory. This should be the path to the cloned HHVM git repository where you
compiled the project.

```bash
$ export HPHP_HOME=/path/to/hhvm
```

Execute this project's `build.sh` script:

```bash
$ ./build.sh
```

This script checks for the HHVM path, executes `hphpize` to prepare the build
process, and finally executes `cmake` and `make` to compile the extension.

The build process will produce a `mongo.so` file, which can then be dynamically
loaded by HHVM by adding the following to HHVM's `config.hdf` file:

```
DynamicExtensions {
  mongo = /path/to/mongo.so
}
```

This example is taken from the
[Extension API](https://github.com/facebook/hhvm/wiki/Extension-API)
documentation.

Note that the `mongo` key in this example is a placeholder; HHVM only cares that
the path to the `mongo.so` file is correct. You may notice that in our test
script, we use `0` as a key when specifying our extension via the command line.

## Tests

The test suite is implemented with [PHPUnit](http://phpunit.de) and may be
executed via the `test.sh` script:

```
$ ./test.sh
```

The test script depends on the `HPHP_HOME` environment variable and will attempt
to locate PHPUnit via the `which` command, so ensure that the `phpunit` binary
is installed in an executable path.
