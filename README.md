MongoDB Extension for HipHop (HHVM)
==================================

This is an implementation of the `MongoDB` PHP extension for the [HipHop PHP VM][fb-hphp].

## Prerequisites

Installation requires a copy of HHVM (__version >= 3.0.0__)to be **built from source** on the local machine, instructions
on how to do this are available on the [HipHop Wiki][fb-wiki].

Secondly, ensure that libmongoc (__version >= 0.94.0__) is installed. Instructions for installing
libmongoc may be found [here](https://github.com/mongodb/mongo-c-driver#fetch-sources-and-build).

## Building and installation

Firstly, ensure that the `$HPHP_HOME` env var has been set:

~~~
export HPHP_HOME=/path/to/hhvm
~~~~

Then the build proper:

~~~
$ cd /path/to/extension
$ $HPHP_HOME/hphp/tools/hphpize/hphpize
$ cmake .
$ ./build.sh
~~~

This will produce a `mongo.so` file, the dynamically-loadable extension. For now, please use the build script to make the files from inside the extension folder.

## Tests

Download and install [PHPUnit](http://phpunit.de/getting-started.html). Ensure that the phpunit binary is located at /usr/local/bin/phpunit. The following script will run the test suite.

~~~
$ ./test.sh
~~~

From the shell (assuming that you're in the mongo-hhvm-driver folder, and the config.hdf file is in there too), run the following to make sure that the dummy extension is set up properly.

~~~
${HPHP_HOME}/hphp/hhvm/hhvm /usr/local/bin/phpunit test/mongoTest.php --config config.hdf
~~~

## Documentation

[fb-hphp]: https://github.com/facebook/hhvm "HipHop PHP"
[fb-wiki]: https://github.com/facebook/hhvm/wiki "HipHop Wiki"
