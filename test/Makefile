all:
	cp -p ../dict/dict.utf8.txt /tmp/
	cp -p ../dict/rules.utf8.ini /tmp/
	cp -p course.xml /tmp
	rm -rf /tmp/test_chinese*
	/tmp/sphinx_bin/bin/indexer -c course.conf course
	/tmp/sphinx_bin/bin/indextool -c course.conf --dumpdict course
