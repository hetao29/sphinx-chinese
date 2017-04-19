all:
	cp dict.txt /tmp/dict.txt
	cp course.xml /tmp
	rm -rf /tmp/test_chinese*
	/tmp/sphinx_bin/bin/indexer -c course.conf course
	/tmp/sphinx_bin/bin/indextool -c course.conf --dumpdict course
