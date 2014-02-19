mongo-hhvm-driver-repository
============================

To get the test extension up and running:
0) chmod 755 build.sh test.sh (if their permissions aren't set like
   that already)
1) run ./build.sh in the shell 
   (you need to set the $HPHP_HOME variable to the location of 
    the HHVM directory -- in my case $HOME\dev\hhvm -- 
    so that you will be able to use the command 
    hphpize). Additionally, this means that you need to git clone
    all the HHVM code rather than just downloading the binary (as far
    as I can tell)
2) run ./test.sh to run the mongoTest.php file and make sure everything
   is working properly.
