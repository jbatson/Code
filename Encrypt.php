<?php
class EncryptData
{
	private $encryptionAlgorithm;
	private $encryptionData;
	private $encryptionKey;
	private $encryptionKeySize;
	private $encryptionIv;
	public  $keyCreated;	
	public  $encryptedData;
	public  $decryptedData;
	
	public function __construct($key = '')
	{
		if($this->CheckAlgorithm() === false)
		{
			// error could not find suitable encryption alogrithm
			exit();
		}
		
		$this->setEncryptionIv(mcrypt_create_iv(mcrypt_get_iv_size($this->getEncryptionAlgorithm(), MCRYPT_MODE_CBC)));
		
		if(isset($key) && !empty($key))
		{
			$this->setEncryptionKey($key);
		}
		else
		{
			$this->GenerateKey($this->GetKeySize());
		}	
	}
	
	/*
	 * Convert the data
	 */
	
	public function Convert($data, $type)
	{
		switch($type)
		{
			case 'encrypt':
				$this->setEncryptionData($data);
				$this->Encrypt();
			break;
			case 'decrypt':
				$this->setEncryptionData(base64_decode($data));
				$this->Decrypt();
			break;
			default:
				// error
				exit();
			break;	
		}		
	}
	
	/*
	 * Encrypts data
	 */ 
	
	private function Encrypt()
	{
		$this->setEncryptedData(base64_encode(mcrypt_encrypt($this->getEncryptionAlgorithm(), $this->getEncryptionKey(), $this->getEncryptionData(), MCRYPT_MODE_CBC, $this->getEncryptionIv())));
	}

	 /*
	  * Decrypts data
	  */
	   
	private function Decrypt()
	{
		$this->setDecryptedData(mcrypt_decrypt($this->getEncryptionAlgorithm(), $this->getEncryptionKey(), $this->getEncryptionData(), MCRYPT_MODE_CBC, $this->getEncryptionIv()));
	}
	
	/*
	 * Check available algorithms from strongest, if available use it
	 */
	
	private function CheckAlgorithm()
	{
		$availableAlgorithms = mcrypt_list_algorithms();
		
		if(in_array('rijndael-256', $availableAlgorithms))
		{
			$this->setEncryptionAlgorithm(MCRYPT_RIJNDAEL_256);	
		}
		else 
		{
			if(in_array('rijndael-128', $availableAlgorithms))
			{
				$this->setEncryptionAlgorithm(MCRYPT_RIJNDAEL_128);	
			}
			else 
			{			
				if(in_array('gost', $availableAlgorithms))
				{
					$this->setEncryptionAlgorithm(MCRYPT_GOST);	
				}
				else 
				{
					if(in_array('serpent', $availableAlgorithms))
					{
						$this->setEncryptionAlgorithm(MCRYPT_SERPENT);	
					}
					else 
					{
						if(in_array('twofish', $availableAlgorithms))
						{
							$this->setEncryptionAlgorithm(MCRYPT_TWOFISH);	
						}
						else 
						{
							return false;
						}
					}
				}
			}
		}
	}
	
	private function GetKeySize()
	{
		return $this->setEncryptionKeySize(mcrypt_enc_get_key_size(mcrypt_module_open($this->getEncryptionAlgorithm(), '', MCRYPT_MODE_CBC, '')));
	}
	
	/*
	 * Generate ecryption key and write to file 
	 */
	
	private function GenerateKey()
	{
		if($this->getEncryptionKeySize() > 0)
		{
			$this->setEncryptionKey(substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $this->getEncryptionKeySize()));
		}
		
		$keyData = '<?php'."\n".'$key = \''.$this->getEncryptionKey().'\';'."\n".'?>';
		
		if($this->WriteData(str_replace('/public_html','',$_SERVER['DOCUMENT_ROOT']).'/wabw/includes/config/key.php', $keyData) === true)
		{
			$this->setKeyCreated(true);
			return true;
		}
		else 
		{
			$this->setKeyCreated(false);
			return false;
		}
	}
	
	/*
	 * Write data to file
	 */
	
	public function WriteConfig($filename, $data)
	{
		$this->WriteData($filename, $data);
	}
	
	private function WriteData($filename, $data)
	{
		if(file_put_contents($filename, $data))
		{
			chmod($filename, 440);
			return true;	
		}	
		else 
		{
			return false;	
		}
	}	
	
	/*
	 * Getters/Setters
	 */
	
	private function setEncryptionData($data)
	{
		$this->encryptionData = $data;
	}
	
	private function getEncryptionData()
	{
		return $this->encryptionData;	
	}
	
	private function setEncryptionKey($key)
	{
		$this->encryptionKey = $key;
	}
	
	private function getEncryptionKey()
	{
		return $this->encryptionKey;
	}	
	
	private function setEncryptionIv($iv)
	{
		$this->encryptionIv = $iv;	
	}

	private function getEncryptionIv()
	{
		return $this->encryptionIv;	
	}
	
	private function setEncryptionAlgorithm($algoritm)
	{
		$this->encryptionAlgorithm = $algoritm;	
	}
	
	private function getEncryptionAlgorithm()
	{
		return $this->encryptionAlgorithm;
	}
	
	private function setEncryptedData($eData)
	{
		$this->encryptedData = $eData;
	}
	
	private function getEncryptedData()
	{
		return $this->encryptedData;
	}	
	
	private function setDecryptedData($dData)
	{
		$this->decryptedData = $dData;
	}
	
	private function getDecryptedData()
	{
		return $this->decryptedData;
	}
	
	private function setEncryptionKeySize($algoritm)
	{
		$this->encryptionKeySize = $algoritm;
	}		
	
	private function getEncryptionKeySize()
	{
		return $this->encryptionKeySize;
	}
	
	private function setKeyCreated($bool)
	{
		$this->keyCreated = $bool;
	}		
	
	private function getKeyCreated()
	{
		return $this->keyCreated;
	}			
}		
?>