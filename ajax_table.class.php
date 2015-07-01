<?php

	/*
	* Add edit delete rows dynamically using jquery and php
	*
	*
	* @version
	* 2.0 (4/19/2014)
	* 
	* @copyright
	* Copyright (C) 2014-2015 
	*
	*
	*
	*
	*
	* @license
	* This file is part of Add edit delete rows dynamically using jquery and php.
	* 
	* Add edit delete rows dynamically using jquery and php is freeware script. you can redistribute it and/or 
	* modify it under the terms of the GNU Lesser General Public License as published by
	* the Free Software Foundation, either version 3 of the License, or
	* (at your option) any later version.
	* 
	* Add edit delete rows dynamically using jquery and php is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	* GNU General Public License for more details.
	* 
	* You should have received a copy of the GNU General Public License
	* along with this script.  If not, see <http://www.gnu.org/copyleft/lesser.html>.
	*/

include('config.php');

class ajax_table {
     
  public function __construct(){
	$this->dbconnect();
  }

   public function dbconnect() {

   $this->conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD)

      or die ("<div style='color:red;'><h3>Could not connect to MySQL server</h3></div>");
         
    mysqli_select_db($this->conn,DB_DB)
      or die ("<div style='color:red;'><h3>Could not select the indicated database</h3></div>");

    return $this->conn;
  }

  function getRecords(){

      if(!isset($_GET["page"])&& empty($_GET["page"])){
          $page=1;
      }
      else{
$page=$_GET["page"];}

      if($page==""||$page==1){
          $page=0;
      }
      else{
          $page=($page*10)-10;
      }
     // $res=mysql_query("select* from locations limit $page,20");
//
//      $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD)
//      or die ("<div style='color:red;'><h3>Could not connect to MySQL server</h3></div>");
//
//      mysqli_select_db($conn,DB_DB)
//      or die ("<div style='color:red;'><h3>Could not select the indicated database</h3></div>");

      $this->res = mysqli_query($this->conn,"select * from locations limit $page,10");
	if(mysqli_num_rows($this->res)){
		while($this->row = mysqli_fetch_assoc($this->res)){
			$record = array_map('stripslashes', $this->row);
			$this->records[] = $record;
		}
		return $this->records;
	}
	//else echo "No records found";
  }



  function save($data){
	if(count($data)){
		$values = implode("','", array_values($data));
		mysqli_query($this->conn,"insert into locations (".implode(",",array_keys($data)).") values ('".$values."')");
		
		if(mysqli_insert_id($this->conn)) return mysqli_insert_id($this->conn);
		return 0;
	}
	else return 0;	
  }	

  function delete_record($id){
	 if($id){
		mysqli_query($this->conn,"delete from locations where id = $id limit 1");
		return mysqli_affected_rows($this->conn);
	 }
  }	

  function update_record($data){
	if(count($data)){
		$id = $data['rid'];
		unset($data['rid']);
		$values = implode("','", array_values($data));
		$str = "";
		foreach($data as $key=>$val){
			$str .= $key."='".$val."',";
		}
		$str = substr($str,0,-1);
		$sql = "update locations set $str where id = $id limit 1";

		$res = mysqli_query($this->conn,$sql);
		
		if(mysqli_affected_rows($this->conn)) return $id;
		return 0;
	}
	else return 0;	
  }	

  function update_column($data){
	if(count($data)){
		$id = $data['rid'];
		unset($data['rid']);
		$sql = "update locations set ".key($data)."='".$data[key($data)]."' where id = $id limit 1";
		$res = mysqli_query($this->conn,$sql);
		if(mysqli_affected_rows($this->conn)) return $id;
		return 0;
		
	}	
  }

  function error($act){
	 return json_encode(array("success" => "0","action" => $act));
  }

}
?>