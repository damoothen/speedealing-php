<?php

include 'main.inc.php';

$olddir = DOL_DOCUMENT_ROOT.'/langs_old';
$newdir = DOL_DOCUMENT_ROOT.'/langs';
$codelangs=array();

$handle=@opendir($olddir);
if (is_resource($handle))
{
	while (($file = readdir($handle))!==false)
	{
		if (is_dir($olddir.'/'.$file) && substr($file, 0, 1) <> '.')
		{
			$codelangs[] = $file;
		}
	}
	closedir($handle);
}

foreach($codelangs as $code)
{
	$dir = $olddir.'/'.$code.'/';
	$handle=@opendir($dir);
	if (is_resource($handle))
	{
		while (($file = readdir($handle))!==false)
		{
			if (is_readable($dir.$file) && substr($file, dol_strlen($file) - 5) == '.lang')
			{
				$module = substr($file, 0, dol_strlen($file) - 5);
				//if ($module != 'oscommerce') continue;
				$oldlangfile = $olddir.'/'.$code.'/'.$module.'.lang';
				$newlangfile = $newdir.'/'.$code.'/'.$module.'.lang.php';

				$out='';

				if ($fp = @fopen($oldlangfile, "rt")) {
					$out.= '<?php'."\n";
					$out.= '/* Copyright (C) 2012	Regis Houssin	<regis@dolibarr.fr>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */'."\n";

					$out.= "\n";
					$out.= '$'.$module.' = array('."\n";
					while ($line = fgets($fp, 4096)) {
						if ($line[0] != "\n" && $line[0] != " " && ! preg_match('/^# Dolibarr/i',$line) && ! preg_match('/^# Copyright/i',$line) && $line[0] != "/" && $line[0] != "*") {

							$tab = explode('=', $line, 2);
							$key = trim($tab[0]);
							$value = trim($tab[1]);

							if ($line[0] == "#") {
								$out.= "\t\t".str_replace("#","//",$line);
							} else if (! empty($key)) {
								$out.= "\t\t".'\''.$key.'\' => \''.str_replace("'","\'",$value).'\','."\n";
							}
						}
					}
					$out.= ');'."\n";
					$out.= '?>';
				}
				fclose($fp);

				$fp = fopen($newlangfile, "w");
				if($fp)
				{
					clearstatcache();
					fputs($fp, $out);
				}
				fclose($fp);
			}
		}
	}
}

?>