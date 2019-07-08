<?php
	class DBConn{

	    private $current_conn;
	 	
	    // set the database connection
	    public function setConnectionMain(){
	        try{
	            $this->current_conn = new mysqli('139.162.17.143','ngulikin_admin','ngulik1234','ngulikin_master');
	        }catch (Exception $e) {
				return false;
			}
	 
	        return true;
    	}

    	// get the database current connection
    	public function getCurrentConn(){
	        return $this->current_conn;
    	}

    	public function setConnectionClose(){
	        return $this->current_conn->close();
    	}

	}