<?php
// Dump the plain text dictionary from .xdb file used by SCWS
// Usage: php dump_xdb_file.php <xdb file> [output file]
// $Id: $

ini_set('memory_limit', '1024M');
set_time_limit(0);
if (!isset($_SERVER['argv'][1]) || !is_file($_SERVER['argv'][1]))
{
	echo "Usage: {$_SERVER['argv'][0]} <xdb file> [output file]\n";
	exit(0);
}

$output = isset($_SERVER['argv'][2]) ? $_SERVER['argv'][2] : 'php://stdout';
if (!($fd = @fopen($output, 'w')))
{
	echo "ERROR: can not open the output file: {$output}\n";
	exit(0);
}

require 'xdb.class.php';
$xdb = new XTreeDB;
if (!$xdb->Open($_SERVER['argv'][1]))
{
	fclose($fd);
	echo "ERROR: input file {$_SERVER['argv'][1]} maybe not a valid XDB file.\n";
	exit(0);
}

$line = "# WORD\tTF\tIDF\tATTR\n";
fwrite($fd, $line);
$xdb->Reset();
$lines=array();
while ($tmp = $xdb->Next())
{
	if (strlen($tmp['value']) != 12) continue;
	$word = $tmp['key'];
	$data = unpack("ftf/fidf/Cflag/a3attr", $tmp['value']);
	if (!($data['flag'] & 0x01)) continue;

	$lines[] = array(trim($word), trim($data['tf']), trim($data['idf']), trim($data['attr']));
}
function cmp($a, $b)
{
	if ($a[1] == $b[1]) {
		return 0;
	}
	return ($a[1] < $b[1]) ? 1 : -1;
}
usort($lines, "cmp");
foreach($lines as $data){
	$line = sprintf("%s\t%.2f\t%.2f\t%.2s\n", $data[0],$data[1],$data[2],$data[3]);
	fwrite($fd, $line);
}
fclose($fd);
$xdb->Close();
?>
