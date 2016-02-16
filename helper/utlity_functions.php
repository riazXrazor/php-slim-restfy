<?php
/**
 * Create a Random String
 *
 * Useful for generating passwords or hashes.
 *
 * @access	public
 * @param	string	type of random string.  basic, alpha, alunum, numeric, nozero, unique, md5, encrypt and sha1
 * @param	integer	number of characters
 * @return	string
 */
if ( ! function_exists('random_string'))
{
	function random_string($type = 'alnum', $len = 8)
	{
		switch($type)
		{
			case 'basic'	: return mt_rand();
				break;
			case 'alnum'	:
			case 'numeric'	:
			case 'nozero'	:
			case 'alpha'	:

					switch ($type)
					{
						case 'alpha'	:	$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
							break;
						case 'alnum'	:	$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
							break;
						case 'numeric'	:	$pool = '0123456789';
							break;
						case 'nozero'	:	$pool = '123456789';
							break;
					}

					$str = '';
					for ($i=0; $i < $len; $i++)
					{
						$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
					}
					return $str;
				break;
			case 'unique'	:
			case 'md5'		:

						return md5(uniqid(mt_rand()));
				break;
			case 'encrypt'	:
			case 'sha1'	:

						$CI =& get_instance();
						$CI->load->helper('security');

						return do_hash(uniqid(mt_rand(), TRUE), 'sha1');
				break;
		}
	}
}

function upload_image($files,$dir='./',$extra=array())
{
	$res = array();
	if(!empty($files['name']))
	{
		$name = $files['name'];
		$size = $files['size'];
		$tmp = $files['tmp_name'];
		$ext = @end(explode('.',$name));
		$uploadnamename = $name;
		if(!empty($extra))
		{
			if(!empty($extra['ext']))
			{
				if(!in_array($ext,$extra['ext']))
				{
			   $res['msg'] = "invalid extension";
			   $res['result'] = false;
   				return $res;
				}
			}

			if(!empty($extra['max_size']))
			{	
				if($size > $extra['max_size'])
				{
			   $res['msg'] = "file too large";
			   $res['result'] = false;
   				return $res;
				}
			}

			if(!empty($extra['rand_name']))
			{
				$randval = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', microtime());
				$uploadnamename = $randval.'.'.$ext;
			}

			if(!empty($extra['prefix']))
			{
				$uploadnamename = $extra['prefix'].$uploadnamename;
			}

		}

		if(!is_dir($dir))
		{
			   $res['msg'] = "invalid upload path";
			   $res['result'] = false;
   				return $res;
		}

		if(empty($tmp))
		{
			   $res['msg'] = "temp file missing";
			   $res['result'] = false;
   				return $res;
		}

		if(!is_writable($dir))
		{
			   $res['msg'] = "directory unwritable";
			   $res['result'] = false;
   				return $res;
		}



		if(move_uploaded_file($tmp,$dir.$uploadnamename))
		{
			$res['msg'] = "";
			$res['result'] = array(
				'full_upload_path' =>$dir.$uploadnamename, 
				'upload_path' => $dir,
				'name' => $uploadnamename,
				'size' => $size
			);
			return $res;
		}
		else
		{
			   $res['msg'] = "upload error";
			   $res['result'] = false;
   				return $res;
		}

	}
 else
 {
   $res['msg'] = "files array empty";
   $res['result'] = false;
   return $res;
  }		
}

