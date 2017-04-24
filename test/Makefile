all:
	cp dict/dict.utf8.txt /tmp/dict
	cp course.xml /tmp
	rm -rf /tmp/test_chinese*
	/tmp/sphinx_bin/bin/indexer -c course.conf course
	/tmp/sphinx_bin/bin/indextool -c course.conf --dumpdict course
