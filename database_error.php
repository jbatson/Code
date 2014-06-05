<?php
/*
 * Cinnamon Website Management System
 * File: database_error.php
 * 
 * +TERMS+
 */

require_once(BASEPATH.'/application/cinnamon/core/error/error.php'); 
 
class Cinnamon_Database_Error extends Cinnamon_Error
{
    public $dbError;
    public $dbErrorNumber;
    public $dbErrorLine;
    public $dbErrorFile;
    public $dbErrorClassMethod;
    public $errorMessage;
    
    public function Database_Error($message, $line, $file, $method)
    {
        $this->setDbError(true);
        $this->setDbErrorNumber(mysql_errno().':'.mysql_error());
        $this->setDbErrorLine($line);
        $this->setDbErrorFile($file);
        $this->setDbErrorClassMethod($method);
        $this->setErrorMessage($message);

        if(LOG_ERRORS === true)
        {
            $logError = parent::Log_Error($this);
            
            if($logError !== true)
            {
                $this->setErrorMessage($logError);   
            }   
        }
        
        return $this->getErrorMessage();
    }

    /**
     * TODO
     * ** create mysqli extensiblity
     */

    
    /**
     * Returns $dbError.
     *
     * @see Cinnamon_Database::$dbError
     */
    private function getDbError() {
        return $this->dbError;
    }
    
    /**
     * Sets $dbError.
     *
     * @param object $dbError
     * @see Cinnamon_Database::$dbError
     */
    private function setDbError($dbError) {
        $this->dbError = $dbError;
    }
    
    /**
     * Returns $dbErrorFile.
     *
     * @see Cinnamon_Database::$dbErrorFile
     */
    private function getDbErrorFile() {
        return $this->dbErrorFile;
    }
    
    /**
     * Sets $dbErrorFile.
     *
     * @param object $dbErrorFile
     * @see Cinnamon_Database::$dbErrorFile
     */
    private function setDbErrorFile($dbErrorFile) {
        $this->dbErrorFile = $dbErrorFile;
    }
    
    /**
     * Returns $dbErrorLine.
     *
     * @see Cinnamon_Database::$dbErrorLine
     */
    private function getDbErrorLine() {
        return $this->dbErrorLine;
    }
    
    /**
     * Sets $dbErrorLine.
     *
     * @param object $dbErrorLine
     * @see Cinnamon_Database::$dbErrorLine
     */
    private function setDbErrorLine($dbErrorLine) {
        $this->dbErrorLine = $dbErrorLine;
    }
    
    /**
     * Returns $dbErrorNumber.
     *
     * @see Cinnamon_Database::$dbErrorNumber
     */
    private function getDbErrorNumber() {
        return $this->dbErrorNumber;
    }
    
    /**
     * Sets $dbErrorNumber.
     *
     * @param object $dbErrorNumber
     * @see Cinnamon_Database::$dbErrorNumber
     */
    private function setDbErrorNumber($dbErrorNumber) {
        $this->dbErrorNumber = $dbErrorNumber;
    }
    
    
    /**
     * Returns $dbErrorClassMethod.
     *
     * @see Cinnamon_Database::$dbErrorClassMethod
     */
    private function getDbErrorClassMethod() {
        return $this->dbErrorClassMethod;
    }
    
    /**
     * Sets $dbErrorClassMethod.
     *
     * @param object $dbErrorClassMethod
     * @see Cinnamon_Database::$dbErrorClassMethod
     */
    private function setDbErrorClassMethod($dbErrorClassMethod) {
        $this->dbErrorClassMethod = $dbErrorClassMethod;
    }
    
    /**
     * Returns $errorMessage.
     *
     * @see Cinnamon_Database::$errorMessage
     */
    private function getErrorMessage() {
        return $this->errorMessage;
    }
    
    /**
     * Sets $errorMessage.
     *
     * @param object $errorMessage
     * @see Cinnamon_Database::$errorMessage
     */
    private function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
    }
}
?>