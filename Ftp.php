<?php
/*
 * Class Ftp 
 * @version 1.0
 * @date 07/14/10
 * @compatibility: PHP 4, PHP 5
 */
class Ftp
{
	/**
	 * @var string $ftpUsername
	 * @var string $ftpPassword
	 * @var string $ftpHost
	 * @var string $ftpDirectory
	 * @var string $ftpFile
	 * @var string $ftpDirectoryPerms
	 * @var string $ftpFilePerms 
	 */
	
	var $ftpUsername;
	var $ftpPassword;
	var $ftpHost;
	var $ftpDirectory;
	var $ftpFile;
	var $ftpDirectoryPerms;
	var $ftpFilePerms;
	var $ftpConnection;
	var $ftpStream;
	var $ftpMode;
	
	/**
	 * @function Ftp
	 * @access public
	 * @return true || false
	 * @param object $ftp_host
	 * @param object $ftp_user
	 * @param object $ftp_passowrd
	 */
	
	function FtpConnect($ftp_host, $ftp_user, $ftp_passowrd)
	{
		$this->setFtpHost($ftp_host);
		$this->setFtpUsername($ftp_user);
		$this->setFtpPassword($ftp_passowrd);
		
		$this->ftpStream = ftp_connect($this->getFtpHost());
		
		if($this->ftpConnection = ftp_login($this->ftpStream, $this->getFtpUsername(), $this->getFtpPassword()))
		{
			return true;	
		}
		else
		{
			return false;	
		}
	}
	
	/**
	 * @function FtpClose
	 * @return true || false
	 */
	
	function FtpClose()
	{
		if(ftp_close($this->ftpStream))
		{
			return true;	
		}
		else
		{
			return false;	
		}
	}
	
	/**
	 * @function CreateDirectory
	 * @return true || false
	 * @param object $ftp_directory
	 * @param object $ftp_permission
	 */
	
	function CreateDirectory($ftp_directory, $ftp_permission)
	{
		if(ftp_mkdir($this->ftpStream, $ftp_directory) !== false)
		{
			$this->DirectoryPermisssions($ftp_directory, $ftp_permission);
			
			return true;
		}
		else
		{		
			return false;	
		}
	}
	
	/**
	 * @function DirectoryPermisssions
	 * @return true || false
	 * @param object $ftp_directory
	 * @param object $ftp_permission
	 */
	
	function DirectoryPermisssions($ftp_directory, $ftp_permission)
	{
		if(ftp_chmod($this->ftpStream, $ftp_permission, $ftp_directory) !== false)
		{
			return true;	
		}
		else
		{	
			return false;	
		}
	}
	
	/**
	 * @function UploadFile
	 * @return true || false
	 * @param object $ftp_directory
	 * @param object $ftp_file
	 * @param object $local_directory
	 * @param object $local_file
	 */
	
	function UploadFile($remote_file, $local_file)
	{
		$this->FtpMode($remote_file);
		
		if(ftp_put($this->ftpStream, $remote_file, $local_file, $this->ftpMode) === true)
		{
			return true;	
		}
		else
		{
			die('FILE: '.__FILE__.' (LINE: '.__LINE__.')');			
			return false;	
		}
	}
	
	/**
	 * @function FtpMode
	 * @return string
	 * @param object $file_name
	 */
	
	function FtpMode($file_name)
	{
		$fileExtension = strtolower(substr($file_name, strrpos($file_name, '.') + 1));
		
		switch($fileExtension)
		{
			case "jpg":
				$this->ftpMode = FTP_BINARY;
			break;
			case "gif":
				$this->ftpMode = FTP_BINARY;
			break;
			case "png":
				$this->ftpMode = FTP_BINARY;
			break;
			case "csv":
				$this->ftpMode = FTP_BINARY;
			break;
			case "tsv":
				$this->ftpMode = FTP_BINARY;
			break;
			case "xls":
				$this->ftpMode = FTP_BINARY;
			break;
			case "doc":
				$this->ftpMode = FTP_ASCII;
			break;
			case "txt":
				$this->ftpMode = FTP_ASCII;
			break;
			case "rtf":
				$this->ftpMode = FTP_ASCII;
			break;
			default:
				$this->ftpMode = FTP_ASCII;
			break;																													
		}
		
		return $this->ftpMode;
		
	}
	
	function FtpDelete($ftp_directory, $file_name)
	{
		if(ftp_chdir($this->ftpStream, $ftp_directory))
		{
			if(ftp_delete($this->ftpStream, $file_name))
			{
				return true;	
			}
			else
			{
				return false;	
			}
		}
		else
		{
			return false;	
		}
	}
	
	
    /**
     * Returns $ftpDirectory.
     * @see Ftp::$ftpDirectory
     */
    function getFtpDirectory()
    {
        return $this->ftpDirectory;
    }
    
    /**
     * Sets $ftpDirectory.
     * @param object $ftpDirectory
     * @see Ftp::$ftpDirectory
     */
    function setFtpDirectory($ftpDirectory)
    {
        $this->ftpDirectory = $ftpDirectory;
    }
    
    /**
     * Returns $ftpDirectoryPerms.
     * @see Ftp::$ftpDirectoryPerms
     */
    function getFtpDirectoryPerms()
    {
        return $this->ftpDirectoryPerms;
    }
    
    /**
     * Sets $ftpDirectoryPerms.
     * @param object $ftpDirectoryPerms
     * @see Ftp::$ftpDirectoryPerms
     */
    function setFtpDirectoryPerms($ftpDirectoryPerms)
    {
        $this->ftpDirectoryPerms = $ftpDirectoryPerms;
    }
    
    /**
     * Returns $ftpFile.
     * @see Ftp::$ftpFile
     */
    function getFtpFile()
    {
        return $this->ftpFile;
    }
    
    /**
     * Sets $ftpFile.
     * @param object $ftpFile
     * @see Ftp::$ftpFile
     */
    function setFtpFile($ftpFile)
    {
        $this->ftpFile = $ftpFile;
    }
    
    /**
     * Returns $ftpFilePerms.
     * @see Ftp::$ftpFilePerms
     */
    function getFtpFilePerms()
    {
        return $this->ftpFilePerms;
    }
    
    /**
     * Sets $ftpFilePerms.
     * @param object $ftpFilePerms
     * @see Ftp::$ftpFilePerms
     */
    function setFtpFilePerms($ftpFilePerms)
    {
        $this->ftpFilePerms = $ftpFilePerms;
    }
    
    /**
     * Returns $ftpHost.
     * @see Ftp::$ftpHost
     */
    function getFtpHost()
    {
        return $this->ftpHost;
    }
    
    /**
     * Sets $ftpHost.
     * @param object $ftpHost
     * @see Ftp::$ftpHost
     */
    function setFtpHost($ftpHost)
    {
        $this->ftpHost = $ftpHost;
    }
    
    /**
     * Returns $ftpPassword.
     * @see Ftp::$ftpPassword
     */
    function getFtpPassword()
    {
        return $this->ftpPassword;
    }
    
    /**
     * Sets $ftpPassword.
     * @param object $ftpPassword
     * @see Ftp::$ftpPassword
     */
    function setFtpPassword($ftpPassword)
    {
        $this->ftpPassword = $ftpPassword;
    }
    
    /**
     * Returns $ftpUsername.
     * @see Ftp::$ftpUsername
     */
    function getFtpUsername()
    {
        return $this->ftpUsername;
    }
    
    /**
     * Sets $ftpUsername.
     * @param object $ftpUsername
     * @see Ftp::$ftpUsername
     */
    function setFtpUsername($ftpUsername)
    {
        $this->ftpUsername = $ftpUsername;
    }
}
?>