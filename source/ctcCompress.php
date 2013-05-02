<?php

/*
 * ctcCompress v1.0.0
 *
 * Copyright (c) 2013 Andrew G. Johnson <andrew@andrewgjohnson.com>
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author Andrew G. Johnson <andrew@andrewgjohnson.com>
 * @copyright Copyright (c) 2013 Andrew G. Johnson <andrew@andrewgjohnson.com>
 * @link http://github.com/ctcCompress/ctcCompress
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @version 1.0.0
 * @package ctcCompress
 *
 */

ini_set('display_errors',0);

function ctcCompress_error()
{
	header('HTTP/1.1 404 Not Found');
	die();
}

if (!isset($_SERVER) || !isset($_SERVER['REQUEST_URI']) || !isset($_SERVER['DOCUMENT_ROOT']) || !isset($_SERVER['SCRIPT_FILENAME']))
	ctcCompress_error();

$_SERVER['DOCUMENT_ROOT'] = rtrim($_SERVER['DOCUMENT_ROOT'],'/\\');

$file_path = $_SERVER['REQUEST_URI'];
$directories_count = sizeof(explode('/',str_replace($_SERVER['DOCUMENT_ROOT'],'',$_SERVER['SCRIPT_FILENAME']))) - 2;
if ($directories_count > 0)
{
	for ($i = 0;$i < $directories_count;$i++)
		$file_path = '/..' . $file_path;
}
$file_path = trim($file_path,'/');
if (preg_match('/^(?P<beginning>[a-zA-Z0-9-_\\/\\.]+)\\.min\\.([0-9]+\\.)(?P<end>css|js)$/',$file_path,$regex_results))
	$file_path = $regex_results['beginning'] . '.min.' . $regex_results['end'];

if (!file_exists($file_path) || !($info = stat($file_path)))
	ctcCompress_error();

header('Date:' . gmdate('D, j M Y H:i:s e',time()));
header('Last-Modified:' . gmdate('D, j M Y H:i:s e',$info['mtime']));
header('Etag:' . sprintf('"%x%x%x"',$info['ino'],$info['size'],$info['mtime']));
header('Accept-Ranges:bytes');

if (strpos($_SERVER['SERVER_NAME'],'local.') === 0)
{
	header('Cache-Control:no-cache,must-revalidate');
	header('Expires:' . gmdate('D, j M Y H:i:s e',time() - (60 * 60 * 24)));
}
else
{
	header('Cache-Control:max-age=' . (60 * 60 * 24 * 7));
	header('Expires:' . gmdate('D, j M Y H:i:s e',time() + (60 * 60 * 24 * 7)));
}

if (substr($file_path,-3) == 'css')
	header('Content-Type:text/css');
else if (substr($file_path,-2) == 'js')
	header('Content-Type:application/javascript');
else if (substr($file_path,-3) == 'jpg' || substr($file_path,-4) == 'jpeg')
	header('Content-Type:image/jpeg');
else if (substr($file_path,-3) == 'gif')
	header('Content-Type:image/gif');
else if (substr($file_path,-3) == 'png')
	header('Content-Type:image/png');
else if (substr($file_path,-3) == 'ico')
	header('Content-Type:image/x-icon');

if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && stripos($_SERVER['HTTP_ACCEPT_ENCODING'],'gzip') !== false)
{
	ob_start('ob_gzhandler');
	echo file_get_contents($file_path);
	ob_end_flush();
}
else
	echo file_get_contents($file_path);