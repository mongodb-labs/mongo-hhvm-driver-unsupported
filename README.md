**Note**: This repository is unsupported and no longer under active development. Please see [mongodb-labs/mongo-hhvm-driver-prototype](https://github.com/mongodb-labs/mongo-hhvm-driver-prototype) for our official HHVM driver.

----

DISCLAIMER
----------
Please note: all tools/ scripts in this repo are released for use "AS IS" without any warranties of any kind, including, but not limited to their installation, use, or performance. We disclaim any and all warranties, either express or implied, including but not limited to any warranty of noninfringement, merchantability, and/ or fitness for a particular purpose. We do not warrant that the technology will meet your requirements, that the operation thereof will be uninterrupted or error-free, or that any errors will be corrected.
Any use of these scripts and tools is at your own risk. There is no guarantee that they have been through thorough testing in a comparable environment and we are not responsible for any damage or data loss incurred with their use.
You are responsible for reviewing and testing any scripts you run thoroughly before use in any non-testing environment.

# MongoDB driver for HHVM

This is an implementation of the
[MongoDB PHP driver](https://github.com/mongodb/mongo-php-driver) for
[HHVM](https://github.com/facebook/hhvm). It is not feature-complete and should
be considered experimental.

This project is not officially supported and GitHub issues have been disabled.

## Dependencies

Compiling this extension requires the following libraries:

 * HHVM (>=3.1.0) must be compiled from source, since the binary distributions
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

## Interactive Mode

To try out this work in progress for yourself, you can run the extension in interactive mode on HipHop VM via the `interactive_mode.sh` script:

```
$ ./interactive_mode.sh
```

Once in interactive mode, you can execute queries and all implemented methods on your existing local databases. For example, if a `test` database exists with a `students` collection, I can access one document in that collection by running the following commands:

```
hphpd> $cli = new MongoClient();
hphpd> $db = $cli->selectDB('test');
hphpd> $coll = $db->selectCollection('students');
hphpd> $cur = $coll->find()->limit(1);
hphpd> $cur->rewind();
hphpd> var_dump($cur->current());
```

## Credits

Máximo Cuadros created the src/contrib/encode.h, src/contrib/encode.cpp and src/contrib/classes.h files.
