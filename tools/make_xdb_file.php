<?php
// make xdb file from plain text dictionary (only support gbk)
// $Id: $

define('IS_UTF8_TXT',	false);
ini_set('memory_limit', '1024M');
set_time_limit(0);
if (!isset($_SERVER['argv'][1]))
{
	echo "Usage: {$_SERVER['argv'][0]} <xdb file> [input file]\n";
	exit(0);
}

if (!extension_loaded('mbstring'))
{
	echo "Usage: mbstring exteions is required.\n";
	exit(0);
}

$input = isset($_SERVER['argv'][2]) ? $_SERVER['argv'][2] : 'php://stdin';
if (!($fd = @fopen($input, 'r')))
{
	echo "ERROR: can not open the input file: {$input}\n";
	exit(0);
}

//
$output = $_SERVER['argv'][1];
if (file_exists($output))
{
	echo "ERROR: output xdb file exists: $output\n";
	exit(0);
}

require ('xdb.class.php');
$xdb = new XTreeDB;
if (!$xdb->Open($output, 'w'))
{
	echo "ERROR: can not open the XDB to write: $output\n";
	exit(0);
}

// load data
mb_internal_encoding(IS_UTF8_TXT ? 'UTF-8' : 'gbk');
$total = 0;
$rec = array();
echo "INFO: Loading text file data ... ";
while ($line = fgets($fd, 512))
{
	if (substr($line, 0, 1) == '#') continue;
	list($word, $tf, $idf, $attr) = explode("\t", $line, 4);
	$k = (ord($word[0]) + ord($word[1])) & 0x3f;
	$attr = trim($attr);

	if (!isset($rec[$k])) $rec[$k] = array();
	if (!isset($rec[$k][$word]))
	{
		$total++;
		$rec[$k][$word] = array();
	}
	$rec[$k][$word]['tf'] = $tf;
	$rec[$k][$word]['idf'] = $idf;
	$rec[$k][$word]['attr'] = $attr;

	// only support GBK dictionary
	$len = strlen($word);
	$len = mb_strlen($word);
	while ($len > 2)
	{
		$len--;
		$temp = mb_substr($word, 0, $len);
		if (!isset($rec[$k][$temp]))
		{
			$total++;
			$rec[$k][$temp] = array();
		}
		$rec[$k][$temp]['part'] = 1;
	}
}
fclose($fd);

// load ok & try to save it to DBM
echo "OK, Total words=$total\n";
for ($k = 0; $k < 0x40; $k++)
{
	if (!isset($rec[$k])) continue;
	$cnt = 0;
	printf("Inserting [%02d/64] ... ", $k);
	foreach ($rec[$k] as $w => $v)
	{
		$flag = (isset($v['tf']) ? 0x01 : 0);
		if ($v['part']) $flag |= 0x02;
		$data = pack('ffCa3', $v['tf'], $v['idf'], $flag, $v['attr']);
		$xdb->Put($w, $data);
		$cnt++;
	}
	printf("%d Records saved.\n", $cnt);
}

// save
echo "INFO: optimizing ... ";
flush();
$xdb->Optimize();
$xdb->Close();
echo "DONE!\n";
?>