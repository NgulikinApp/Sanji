<?php
	class Menu{

		public function __construct($db){
			$this->conn = $db;
		}

		public function getMenuListHome($const_uri){
			try{
				$uri = $const_uri.'img/';
				$data = array();
                $query = "SELECT 
                                id, 
                                name,
                                concat(?,img) as img_url,
                                concat(?,img_hover) as img_hover_url
                            FROM 
                            	ng_menu";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param('ss',$uri,$uri);
                $stmt->execute(); 
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc()){
                	$data[] = $row;
                }

                return handler\output\GenerateJson($data,'200',true,'OK');
			} catch (Exception $e) {
				return false;
			}
		}
	}