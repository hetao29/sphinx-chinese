# sphinx-chinese
给官方的 sphinx 增加对中文的支持（目前只支持utf8），基于流行的中文分词插件 https://github.com/hightman/scws

# 安装方法
## 安装scws
可以参考 https://github.com/hightman/scws 里的安装方法
```bash
$ cd /tmp
$ wget http://www.xunsearch.com/scws/down/scws-1.2.1.tar.bz2 
$ tar xjf scws-1.2.1.tar.bz2 
$ cd scws-1.2.1
$ ./configure --prefix=/tmp/scws
$ make 
$ make install
$ tree /tmp/scws
```
## 安装sphinx
源码存储在 https://github.com/hetao29/sphinx ，这是官方的分支
```bash
```
## 配置sphinx
具体的手册说明参考 http://www.xunsearch.com/scws/docs.php 
```bash
#支持如下4个参数
#必须设置，开关，注释掉就关掉scws支持
scws = 1 
#可选，如果不设置，默认全是单字切分，词典文件，支持3种官方格式
scws_dict=/Users/hetal/sphinx_new/test/chinese/dict.txt
#可选，规则文件
scws_rule=/Users/hetal/scws/etc/rules.utf8.ini
#可选，默认为0，复合切词，缺省不复合分词。取值由下面几个常量异或组合（也可用 1-15 来表示，就是数字相加，比如3就表示1+2）：
#
# SCWS_MULTI_SHORT   (1)短词
# SCWS_MULTI_DUALITY (2)二元（将相邻的2个单字组合成一个词）
# SCWS_MULTI_ZMAIN   (4)重要单字
# SCWS_MULTI_ZALL    (8)全部单字
scws_multi=15
    
```
