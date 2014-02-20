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

Then the build proper:

~~~
$ cd /path/to/extension
$ $HPHP_HOME/hphp/tools/hphpize/hphpize
$ cmake .
$ make
~~~

This will produce a `mongo.so` file, the dynamically-loadable extension. Alternatively, run build.sh from inside the extension folder.

To enable the extension, you need to have the following section in your hhvm config file (typically config.hdf):

~~~
DynamicExtensionPath = /path/to/hhvm/extensions
DynamicExtensions {
	* = mongo.so
}
~~~

Where `/path/to/hhvm/extensions` is a folder containing all HipHop extensions, and `mongo.so` is in
it. This will cause the extension to be loaded when the virtual machine starts up.

If that doesn't work, you can use the below for now:

~~~
DynamicExtensions {
	mongo = /mongo.so
}
~~~

## Documentation

[fb-hphp]: https://github.com/facebook/hhvm "HipHop PHP"
[fb-wiki]: https://github.com/facebook/hhvm/wiki "HipHop Wiki"
