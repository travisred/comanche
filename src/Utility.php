<?php
	class Utility
	{
		public static function readFileContent($file_path)
		{
			$file_handle = fopen($file_path, 'r');
			$file_content = fread($file_handle, filesize($file_path));
			fclose($file_handle);
			return $file_content;
		}

		public static function writeFileContent($file_path, $file_content)
		{
			$path_pieces = explode('/', $file_path);
			$file_name = array_pop($path_pieces);
			$target_dir = implode('/', $path_pieces);
			if (!is_dir($target_dir)) {
				mkdir($target_dir, 0755, true);
			}
			$file_handle = fopen($target_dir . '/' . $file_name, 'wb');
			fwrite($file_handle, $file_content);
			fclose($file_handle);
		}

		/*
		 * Parses config file. The config is essentially a flat yaml file, but
		 * I wrote this small method instead of bundling a >1k LOC yaml parser.
		 *
		 */

		public static function setConfig($site)
		{
			$config_content = Utility::readFileContent('config.txt');
			$config_content = explode("\n", $config_content);
			foreach ($config_content as $item) {
				if (!empty($item)) {
					$item = explode(': ', $item);
					$site->{$item[0]} = $item[1];
				}
			}
		}
	}

