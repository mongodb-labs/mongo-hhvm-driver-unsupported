MongoDB Extension for HipHop (HHVM)
==================================

This is an implementation of the `MongoDB` PHP extension for the [HipHop PHP VM][fb-hphp].

## Prerequisites

## Building and installation

Installation requires a copy of HipHop to be **built from source** on the local machine, instructions
on how to do this are available on the [HipHop Wiki][fb-wiki]. Once done, the following commands
will build the extension.

Firstly, ensure that the `$HPHP_HOME` env var has been set:

~~~
export HPHP_HOME=/path/to/hhvm
~~~~

Secondly, ensure that libmongoc is installed. Instructions for installing
libmongoc may be found [here](https://github.com/mongodb/mongo-c-driver#fetch-sources-and-build).

Then the build proper:

~~~
$ cd /path/to/extension
$ $HPHP_HOME/hphp/tools/hphpize/hphpize
$ cmake .
$ ./build.sh
~~~

This will produce a `mongo.so` file, the dynamically-loadable extension. For now, please use the build script to make the files from inside the extension folder.

To enable and test the extension, run ./test.sh.

## Tests
From the shell (assuming that you're in the mongo-hhvm-driver folder, and the config.hdf file is in there too), run the following to make sure that the dummy extension is set up properly.

~~~
${HPHP_HOME}/hphp/hhvm/hhvm mongoTest.php --config config.hdf
~~~

## Documentation

[fb-hphp]: https://github.com/facebook/hhvm "HipHop PHP"
[fb-wiki]: https://github.com/facebook/hhvm/wiki "HipHop Wiki"
