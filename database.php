<?php
/*
 * Cinnamon Website Management System
 * File: database.php
 * 
 * +TERMS+
 */

require_once(BASEPATH.'/application/cinnamon/core/database/database_error.php'); 
 
class Cinnamon_Database extends Cinnamon_Database_Error
{
	public $dbConn;
    public $dbError = '';
	public $dbResults;
	private $dbQuery;

	public function __construct()
	{
		$dbConn = mysql_connect(DBHOST, DBUSER, DBPASS);
		
		if(is_resource($dbConn))
		{
			if(!mysql_select_db(DBNAME))
			{
				$this->dbError = parent::Database_Error('Error: Could not connect to database.', __LINE__, __FILE__, __METHOD__);
			}
			else
			{
				$this->setDbConn($dbConn);
				return $this->getDbConn();
			}
		}
		else
		{
			$this->dbError = parent::Database_Error('Error: Could not connect to database.', __LINE__, __FILE__, __METHOD__);
		}
	}


	/**
	 * TODO
	 * ** create mysqli extensiblity
	 */

    /**
     * Returns $dbConn.
     *
     * @see Cinnamon_Database::$dbConn
     */
    private function getDbConn() {
        return $this->dbConn;
    }
    
    /**
     * Sets $dbConn.
     *
     * @param object $dbConn
     * @see Cinnamon_Database::$dbConn
     */
    private function setDbConn($dbConn) {
        $this->dbConn = $dbConn;
    }

    
    /**
     * Returns $dbQuery.
     *
     * @see Cinnamon_Database::$dbQuery
     */
    private function getDbQuery() {
        return $this->dbQuery;
    }
    
    /**
     * Sets $dbQuery.
     *
     * @param object $dbQuery
     * @see Cinnamon_Database::$dbQuery
     */
    private function setDbQuery($dbQuery) {
        $this->dbQuery = $dbQuery;
    }
    
    /**
     * Returns $dbResults.
     *
     * @see Cinnamon_Database::$dbResults
     */
    private function getDbResults() {
        return $this->dbResults;
    }
    
    /**
     * Sets $dbResults.
     *
     * @param object $dbResults
     * @see Cinnamon_Database::$dbResults
     */
    private function setDbResults($dbResults) {
        $this->dbResults = $dbResults;
    }
}
?>