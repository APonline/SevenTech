<?php

class Posts
{
	public $conn;

	public function __construct()
	{
		require_once("class.database.php");
		$db = new DatabaseConnection;
		$this->conn = $db->ConnectDB();
	}



/**
  * Get Featured Home Page content
  *
  *
  **/
  public function getCategories($cat){
  	try{
		$queryInfo = "SELECT * FROM categories WHERE category = :category LIMIT 1";

		$category = $this->conn->prepare($queryInfo);

		$category->setFetchMode(PDO::FETCH_ASSOC);
		$category->bindParam(":category", $cat, PDO::PARAM_INT);
		$category->execute();
		$category = $category->fetchAll();

		if(empty($category)){
			return;
		}
		
		$category = $category[0]['id'];
		
		return $category;

	}catch(PDOException $e){
		echo $e->getMessage();
	}
  }
/**
  * Get Featured Home Page content
  *
  *
  **/
  public function getContent($category){
  	try{
  		$category = $this->getCategories($category);

		$queryInfo = "SELECT * FROM posts WHERE category = :category && active = 1 ORDER BY ordering ASC LIMIT 7";

		$featured = $this->conn->prepare($queryInfo);

		$featured->setFetchMode(PDO::FETCH_ASSOC);
		$featured->bindParam(":category", $category, PDO::PARAM_INT);
		$featured->execute();
		$featured = $featured->fetchAll();

		if(empty($featured)){
			return;
		}

		$featuredList= array();

		for($i=0; $i<count($featured); $i++){
			$mediaList = $this->getMedia($featured[$i]['media']);
			
			foreach($mediaList as $mediaItem){
				if($mediaItem['main']==1){
					$mainMedia = $mediaItem['content'];
				}
			}

			$featuredInfo = array(
				'id' => $featured[$i]['id'],
				'category' => $featured[$i]['category'],
				'topic' => $featured[$i]['topic'],
				'title' => $featured[$i]['title'],
				'dated' => $featured[$i]['dated'],
				'content' => $featured[$i]['content'],
				'media_main' => $mainMedia,
				'media' => $mediaList,
				'sources' => $featured[$i]['sources'],
				'attachment' => $featured[$i]['attachment'],
				'likes' => $featured[$i]['likes'],
				'shares' => $featured[$i]['shares']
			);

			$featuredList[] = $featuredInfo;
		}

		return json_encode($featuredList);

	}catch(PDOException $e){
		echo $e->getMessage();
	}
  }
  
/**
  * Get Media
  *
  *
  **/ 
  public function getMedia($mediaSet){
  	try{
  		$queryInfo = "SELECT * FROM media WHERE set_id = :mediaSet ORDER BY id ASC";

		$media = $this->conn->prepare($queryInfo);

		$media->setFetchMode(PDO::FETCH_ASSOC);
		$media->bindParam(":mediaSet", $mediaSet, PDO::PARAM_INT);
		$media->execute();
		$media = $media->fetchAll();

		if(empty($media)){
			return;
		}

		$mediaList= array();

		for($i=0; $i<count($media); $i++){

			$mediaInfo = array(
				'id' => $media[$i]['id'],
				'set_id' => $media[$i]['set_id'],
				'title' => $media[$i]['title'],
				'content' => $media[$i]['content'],
				'main' => $media[$i]['main'],
				'dated' => $media[$i]['dated']
			);

			$mediaList[] = $mediaInfo;
		}

		return $mediaList;
  	}catch(PDOException $e){
		echo $e->getMessage();
	}
  }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
/**
  * Inventory types
  * @param $userID - the users key in the DB
  * @param descript - brings back all the user record data
  *
  */
	public function getInventoryTypes()
	{
		try{
			$queryInfo = "SELECT * FROM ITS_Statuses ORDER BY id ASC";

			$inventory = $this->conn->prepare($queryInfo);

			$inventory->setFetchMode(PDO::FETCH_ASSOC);
			$inventory->execute();
			$inventory = $inventory->fetchAll();

			if(empty($inventory)){
				return;
			}

			$inventoryList= array();

			for($i=0; $i<count($inventory); $i++){

				$inventoryInfo = array(
					'id' => $inventory[$i]['id'],
					'value' => $inventory[$i]['value']
				);

				$inventoryList[] = $inventoryInfo;
			}

			return json_encode($inventoryList);

		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}

/**
  * User Info Select
  * @param $userID - the users key in the DB
  * @param descript - brings back all the user record data
  *
  */
	public function getAllInventory($selected = null, $auto = null)
	{
		if($selected==1){
			$selected="all";
		}

		try{
			$inventoryList= array(
				'Inventory'=>'',
				'Receiving'=>'',
				'On-Hand'=>'',
				'Shipping'=>'',
				'Aging'=>''
			);

			$inventoryMasterList= array();
			$inventoryReceivingList= array();
			$inventoryOnHandList= array();
			$inventoryShippingList= array();
			$inventoryAgingList= array();

			//inventory
			$inventoryInfo = $this->getInventory($selected,null,$auto);
			$inventoryMasterList[] = $inventoryInfo;
			$inventoryList['Inventory'] = $inventoryMasterList;

			//receiving
			$inventoryInfoReceiving = $this->getInventoryReceiving($selected,null,$auto);
			$inventoryReceivingList[] = $inventoryInfoReceiving;
			$inventoryList['Receiving'] = $inventoryReceivingList;

			//on_hand
			$inventoryInfoOnHand = $this->getInventoryOnHand($selected,null,$auto);
			$inventoryOnHandList[] = $inventoryInfoOnHand;
			$inventoryList['On-Hand'] = $inventoryOnHandList;

			//shipping
			$inventoryInfoShipping = $this->getInventoryShipping($selected,null,$auto);
			$inventoryShippingList[] = $inventoryInfoShipping;
			$inventoryList['Shipping'] = $inventoryShippingList;

			//aging
			$inventoryInfoAging = $this->getInventoryAging($selected,null,$auto);
			$inventoryAgingList[] = $inventoryInfoAging;
			$inventoryList['Aging'] = $inventoryAgingList;

			return json_encode($inventoryList);

		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	/**
	  * MASTER INVENTORY Info Select
	  * @param descript - brings back all inventory from the master table
	  *
	  */
	public function getInventory($selected = null, $itsID = null, $auto = null)
	{
		try{
			if(isset($itsID)){
				$queryInfo = "SELECT * FROM ITS_Inventory WHERE its_id = :its_id ORDER BY its_id DESC LIMIT 1";
			}elseif(isset($selected)&&$selected!=0){
				$queryInfo = "SELECT * FROM ITS_Inventory WHERE client_id = :clientID ORDER BY its_id DESC";
			}else{
				$queryInfo = "SELECT * FROM ITS_Inventory ORDER BY its_id DESC";
			}

			$inventory = $this->conn->prepare($queryInfo);

			if(isset($itsID)){
				$inventory->bindParam(":its_id", $itsID, PDO::PARAM_INT);
			}elseif(isset($selected)&&$selected!=0){
				$inventory->bindParam(":clientID", $selected, PDO::PARAM_INT);
			}

			$inventory->setFetchMode(PDO::FETCH_ASSOC);
			$inventory->execute();
			$inventory = $inventory->fetchAll();

			if(empty($inventory)){
				return;
			}

			$inventoryList= array();

			for($i=0; $i<count($inventory); $i++){

				$inventoryInfo = array(
					'id' => $inventory[$i]['id'],
					'its_id' => $inventory[$i]['its_id'],
					'client_id' => $inventory[$i]['client_id'],
					'part_number' => $inventory[$i]['part_number'],
					'part_name' => htmlspecialchars($inventory[$i]['part_name'], ENT_QUOTES),
					'QTY' => $inventory[$i]['QTY'],
					'QTY_recorded' => $inventory[$i]['QTY_recorded'],
					'QTY_ordered' => $inventory[$i]['QTY_ordered'],
					'QTY_aging' => $inventory[$i]['QTY_aging'],
					'QTY_shipped' => $inventory[$i]['QTY_shipped'],
					'date_entered' => $inventory[$i]['date_entered'],
					'received_date' => $inventory[$i]['received_date'],
					'bin_location' => $inventory[$i]['bin_location'],
					'variance' => $inventory[$i]['variance'],
					'incoming_way_bill' => $inventory[$i]['incoming_way_bill'],
					'serial_number' => $inventory[$i]['serial_number'],
					'comments' => $inventory[$i]['comments']
				);

				$inventoryList[] = $inventoryInfo;
			}

			if($selected==null&&$selected!=0){
				return $inventoryList;
			}elseif($auto!=null){
				return $inventoryList;
			}else{
				if($selected==="all"){
					return $inventoryList;
				}else{
					return json_encode($inventoryList);
				}
			}

		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	public function checkMasterQTY($itsID, $dbCurr, $originQTY)
	{
		try{
			//master
			$queryInfo = "SELECT QTY, QTY_recorded, QTY_discarded, QTY_shipped, QTY_aging FROM ITS_Inventory WHERE its_id = :itsID LIMIT 1";
			$inventory = $this->conn->prepare($queryInfo);
			$inventory->bindParam(":itsID", $itsID, PDO::PARAM_INT);
			$inventory->setFetchMode(PDO::FETCH_ASSOC);
			$inventory->execute();
			$inventory = $inventory->fetchAll();

			if(empty($inventory)){
				return false;
				exit;
			}

			$QTY = $inventory[0]['QTY'];
			$QTYrecorded = $inventory[0]['QTY_recorded'];
			$QTYdiscarded = $inventory[0]['QTY_discarded'];
			$QTYshipped = $inventory[0]['QTY_shipped'];
			$QTYaging = $inventory[0]['QTY_aging'];

			switch($dbCurr){
				case 'Shipping':
					if($originQTY==$QTYshipped&&$QTYrecorded==$QTYshipped||($QTYshipped+$QTYdiscarded)==$QTYrecorded&&$originQTY==$QTY){$fullDelete = 1;}else{$fullDelete = 0;}
				break;
				case 'Aging':
					if($originQTY==$QTYaging&&$QTYrecorded==$QTYaging||($QTYaging+$QTYdiscarded)==$QTYrecorded&&$originQTY==$QTY){$fullDelete = 1;}else{$fullDelete = 0;}
				break;
				case 'Receiving':
					if($originQTY==$QTY&&$QTYrecorded==$QTY||($QTY+$QTYdiscarded)==$QTYrecorded&&$originQTY==$QTY){$fullDelete = 1;}else{$fullDelete = 0;}
				break;
				case 'On-Hand':
					if($originQTY==$QTY&&$QTYrecorded==$QTY||($QTY+$QTYdiscarded)==$QTYrecorded&&$originQTY==$QTY){$fullDelete = 1;}else{$fullDelete = 0;}
				break;
			}

			return $fullDelete;

		}catch(PDOException $e){
			return false;
			exit;
		}
	}

	/**
	  * RECEIVING INVENTORY Info Select
	  * @param descript - brings back all inventory from the receving table
	  *
	  */
	public function getInventoryReceiving($selected = null, $id = null, $auto = null)
	{
		try{
			if(isset($id)){
				$queryInfo = "SELECT * FROM ITS_Receiving WHERE id = :id ORDER BY its_id DESC";
			}elseif(isset($selected)&&$selected!=0){
				$queryInfo = "SELECT * FROM ITS_Receiving WHERE client_id = :clientID ORDER BY its_id DESC";
			}else{
				$queryInfo = "SELECT * FROM ITS_Receiving ORDER BY its_id DESC";
			}

			$inventory = $this->conn->prepare($queryInfo);

			if(isset($id)){
				$inventory->bindParam(":id", $id, PDO::PARAM_STR);
			}elseif(isset($selected)&&$selected!=0){
				$inventory->bindParam(":clientID", $selected, PDO::PARAM_INT);
			}

			$inventory->setFetchMode(PDO::FETCH_ASSOC);
			$inventory->execute();
			$inventory = $inventory->fetchAll();

			if(empty($inventory)){
				return;
			}

			$inventoryList= array();

			for($i=0; $i<count($inventory); $i++){

				$inventoryInfo = array(
					'id' => $inventory[$i]['id'],
					'its_id' => $inventory[$i]['its_id'],
					'client_id' => $inventory[$i]['client_id'],
					'received_date' => $inventory[$i]['received_date'],
					'incoming_way_bill' => $inventory[$i]['incoming_way_bill'],
					'part_number' => $inventory[$i]['part_number'],
					'part_name' => htmlspecialchars($inventory[$i]['part_name'], ENT_QUOTES),
					'serial_number' => $inventory[$i]['serial_number'],
					'bin_location' => $inventory[$i]['bin_location'],
					'QTY' => $inventory[$i]['QTY'],
					'QTY_recorded' => $inventory[$i]['QTY_recorded'],
					'variance' => $inventory[$i]['variance'],
					'comments' => $inventory[$i]['comments']
				);

				$inventoryList[] = $inventoryInfo;
			}

			if($selected==null&&$selected!=0){
				return $inventoryList;
			}elseif($auto!=null){
				return $inventoryList;
			}else{
				if($selected==="all"){
					return $inventoryList;
				}else{
					return json_encode($inventoryList);
				}
			}

		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	/**
	  * ON-HAND INVENTORY Info Select
	  * @param descript - brings back all inventory from the inventory table with select info
	  *
	  */
	public function getInventoryOnHand($selected = null, $id = null, $auto = null)
	{
		try{
			if(isset($id)){
				$queryInfo = "SELECT * FROM ITS_Inventory WHERE id = :id && QTY <= QTY_recorded && QTY_shipped <= QTY_recorded && QTY_discarded != QTY_recorded && QTY != 0 ORDER BY its_id DESC";
			}elseif(isset($selected)&&$selected!=0){
				$queryInfo = "SELECT * FROM ITS_Inventory WHERE client_id = :clientID ORDER BY its_id DESC";
			}else{
				$queryInfo = "SELECT * FROM ITS_Inventory WHERE QTY <= QTY_recorded && QTY_shipped <= QTY_recorded && QTY_discarded != QTY_recorded && QTY != 0 ORDER BY its_id DESC";
			}

			$inventory = $this->conn->prepare($queryInfo);

			if(isset($id)){
				$inventory->bindParam(":id", $id, PDO::PARAM_STR);
			}elseif(isset($selected)&&$selected!=0){
				$inventory->bindParam(":clientID", $selected, PDO::PARAM_INT);
			}

			$inventory->setFetchMode(PDO::FETCH_ASSOC);
			$inventory->execute();
			$inventory = $inventory->fetchAll();

			if(empty($inventory)){
				return;
			}

			$inventoryList= array();

			for($i=0; $i<count($inventory); $i++){

				$inventoryInfo = array(
					'id' => $inventory[$i]['id'],
					'its_id' => $inventory[$i]['its_id'],
					'client_id' => $inventory[$i]['client_id'],
					'part_number' => $inventory[$i]['part_number'],
					'bin_location' => $inventory[$i]['bin_location'],
					'part_name' => htmlspecialchars($inventory[$i]['part_name'], ENT_QUOTES),
					'QTY' => $inventory[$i]['QTY'],
					'comments' => $inventory[$i]['comments']
				);

				$inventoryList[] = $inventoryInfo;
			}

			if($selected==null&&$selected!=0){
				return $inventoryList;
			}elseif($auto!=null){
				return $inventoryList;
			}else{
				if($selected==="all"){
					return $inventoryList;
				}else{
					return json_encode($inventoryList);
				}
			}

		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	/**
	  * SHIPPING INVENTORY Info Select
	  * @param descript - brings back all inventory from the shipping table
	  *
	  */
	public function getInventoryShipping($selected = null,$id = null, $auto = null)
	{
		try{
			if(isset($id)){
				$queryInfo = "SELECT * FROM ITS_Shipping WHERE id = :id && completed = 0000-00-00 ORDER BY its_id DESC";
			}elseif(isset($selected)&&$selected!=0){
				$queryInfo = "SELECT * FROM ITS_Shipping WHERE client_id = :clientID && completed = 0000-00-00 ORDER BY its_id DESC";
			}else{
				$queryInfo = "SELECT * FROM ITS_Shipping WHERE completed = 0000-00-00 ORDER BY its_id DESC";
			}

			$inventory = $this->conn->prepare($queryInfo);

			if(isset($id)){
				$inventory->bindParam(":id", $id, PDO::PARAM_INT);
			}elseif(isset($selected)&&$selected!=0){
				$inventory->bindParam(":clientID", $selected, PDO::PARAM_INT);
			}

			$inventory->setFetchMode(PDO::FETCH_ASSOC);
			$inventory->execute();
			$inventory = $inventory->fetchAll();

			if(empty($inventory)){
				return;
			}

			$inventoryList= array();
			for($i=0; $i<count($inventory); $i++){

				$inventoryInfo = array(
					'id' => $inventory[$i]['id'],
					'its_id' => $inventory[$i]['its_id'],
					'client_id' => $inventory[$i]['client_id'],
					'part_number' => $inventory[$i]['part_number'],
					'part_name' => htmlspecialchars($inventory[$i]['part_name'], ENT_QUOTES),
					'serial_number' => $inventory[$i]['serial_number'],
					'QTY_ordered' => $inventory[$i]['QTY_ordered'],
					'QTY_shipped' => $inventory[$i]['QTY_shipped'],
					'incoming_way_bill' => $inventory[$i]['incoming_way_bill'],
					'carrier' => $inventory[$i]['carrier'],
					'carrier_tracking' => $inventory[$i]['carrier_tracking'],
					'project_reference' => $inventory[$i]['project_reference'],
					'shipping_date' => $inventory[$i]['shipping_date'],
					'completed' => $inventory[$i]['completed']
				);

				$inventoryList[] = $inventoryInfo;
			}

			if($selected==null&&$selected!=0){
				return $inventoryList;
			}elseif($auto!=null){
				return $inventoryList;
			}else{
				if($selected==="all"){
					return $inventoryList;
				}else{
					return json_encode($inventoryList);
				}
			}

		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	/**
	  * AGING INVENTORY Info Select
	  * @param descript - brings back all inventory from the aging table
	  *
	  */
	public function getInventoryAging($selected = null, $id = null, $auto = null)
	{
		try{
			if(isset($id)){
				$queryInfo = "SELECT * FROM ITS_Aging WHERE id = :id ORDER BY its_id DESC";
			}elseif(isset($selected)&&$selected!=0){
				$queryInfo = "SELECT * FROM ITS_Aging WHERE client_id = :clientID ORDER BY its_id DESC";
			}else{
				$queryInfo = "SELECT * FROM ITS_Aging ORDER BY its_id DESC";
			}

			$inventory = $this->conn->prepare($queryInfo);

			if(isset($id)){
				$inventory->bindParam(":id", $id, PDO::PARAM_STR);
			}elseif(isset($selected)&&$selected!=0){
				$inventory->bindParam(":clientID", $selected, PDO::PARAM_INT);
			}

			$inventory->setFetchMode(PDO::FETCH_ASSOC);
			$inventory->execute();
			$inventory = $inventory->fetchAll();

			if(empty($inventory)){
				return;
			}

			$inventoryList= array();

			for($i=0; $i<count($inventory); $i++){

				$inventoryInfo = array(
					'id' => $inventory[$i]['id'],
					'its_id' => $inventory[$i]['its_id'],
					'client_id' => $inventory[$i]['client_id'],
					'part_number' => $inventory[$i]['part_number'],
					'part_name' => htmlspecialchars($inventory[$i]['part_name'], ENT_QUOTES),
					'QTY_aging' => $inventory[$i]['QTY_aging'],
					'received_date' => $inventory[$i]['received_date']
				);

				$inventoryList[] = $inventoryInfo;
			}

			if($selected==null&&$selected!=0){
				return $inventoryList;
			}elseif($auto!=null){
				return $inventoryList;
			}else{
				if($selected==="all"){
					return $inventoryList;
				}else{
					return json_encode($inventoryList);
				}
			}

		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}


/* CONTROLS */
/**
  *Insert New Inventory
  *@param $string : The array of new inventory item to be added
  */
  public function addNewInventory($inventoryData)
  {
  
  		//master
		$queryInfo = "SELECT its_id FROM ITS_Inventory ORDER BY its_id DESC LIMIT 1";
		$inventory = $this->conn->prepare($queryInfo);
		$inventory->setFetchMode(PDO::FETCH_ASSOC);
		$inventory->execute();
		$inventory = $inventory->fetchAll();

		if(empty($inventory)){
			$itsID = 0;
		}else{
			$itsID = $inventory[0]['its_id'];
		}
		
		$itsID = $itsID + 1;
			
    	$item = $inventoryData['item'];
		$clientID = $inventoryData['client_id'];
    	$descript = $inventoryData['descript'];
		$binLocation = $inventoryData['bin_location'];
		$QTY = $inventoryData['qty'];
		$QTYRecorded = $inventoryData['QTY_recorded'];
		$variance = $inventoryData['variance'];
		$incomingWayBill = $inventoryData['incoming_way_bill'];
		$serialNumber = $inventoryData['serial_number'];
		if(isset($inventoryData['comments'])){$comments = $inventoryData['comments'];}else{$comments = "";}

    try{
			$startDate = date("Y-m-d h:i:s");

			$queryInfo = "INSERT INTO ITS_Inventory(its_id, client_id, part_number, part_name, bin_location, QTY, QTY_recorded, date_entered, received_date, variance, incoming_way_bill, serial_number, comments)VALUES(:its_id, :client_id, :part_number, :part_name, :bin_location, :QTY, :QTY_recorded, :date_entered, :received_date, :variance, :incoming_way_bill, :serial_number, :comments)";

			$this->conn->beginTransaction();
			$statement = $this->conn->prepare($queryInfo);
			$statement->execute(array(
				"its_id" => $itsID,
				"client_id" => $clientID,
        		"part_number" => $item,
				"part_name" => $descript,
				"bin_location" => $binLocation,
				"QTY" => $QTY,
				"QTY_recorded" => $QTY,
				"date_entered" => $startDate,
				"received_date" => $startDate,
				"variance" => $variance,
				"incoming_way_bill" => $incomingWayBill,
				"serial_number" => $serialNumber,
				"comments" => $comments
			));


			$queryInfo2 = "INSERT INTO ITS_Receiving(its_id, client_id, part_number, part_name, serial_number, bin_location, QTY, QTY_recorded, variance, incoming_way_bill, received_date, comments)VALUES(:its_id, :client_id, :part_number, :part_name, :serial_number, :bin_location, :QTY, :QTY_recorded, :variance, :incoming_way_bill, :received_date, :comments)";

			$statement2 = $this->conn->prepare($queryInfo2);
			$statement2->execute(array(
				"its_id" => $itsID,
				"client_id" => $clientID,
        		"part_number" => $item,
				"part_name" => $descript,
				"serial_number" => $serialNumber,
				"bin_location" => $binLocation,
				"QTY" => $QTY,
				"QTY_recorded" => $QTY,
				"variance" => $variance,
				"incoming_way_bill" => $incomingWayBill,
				"received_date" => $startDate,
				"comments" => $comments
			));

			$this->conn->commit();
      return true;
      exit;

		}catch(PDOException $e){
			$this->conn->rollback();
			return "exists";
      exit;
		}
  }

/**
  *Insert New Inventory in bulk
  *@param $string : The array of new inventory item to be added
  */
  public function uploadNewInventory($inventoryData)
  {
		foreach($inventoryData as $entry){
			if(isset($entry[0])){$clientID = $entry[0];}else{$clientID="N/A";}
	    	if(isset($entry[1])){$item = $entry[1];}else{$item="N/A";}
			if(isset($entry[2])){$descript = $entry[2];}else{$descript="N/A";}
			if(isset($entry[3])){$binLocation = $entry[3];}else{$binLocation=0;}
			if(isset($entry[4])){$QTY = $entry[4];}else{$QTY=1;}
			if(isset($entry[4])){$QTYRecorded = $entry[4];}else{$QTYRecorded=1;}
			if(isset($entry[5])){$variance = $entry[5];}else{$variance=0;}
			if(isset($entry[6])){$incomingWayBill = $entry[6];}else{$incomingWayBill=0;}
			if(isset($entry[7])){$serialNumber = $entry[7];}else{$serialNumber=0;}
			if(isset($entry[8])){$comments = $entry[8];}else{$comments="";}

	    try{
	    		//master
				$queryInfo = "SELECT its_id FROM ITS_Inventory ORDER BY its_id DESC LIMIT 1";
				$inventory = $this->conn->prepare($queryInfo);
				$inventory->setFetchMode(PDO::FETCH_ASSOC);
				$inventory->execute();
				$inventory = $inventory->fetchAll();

				if(empty($inventory)){
					$itsID = 0;
				}else{
					$itsID = $inventory[0]['its_id'];
				}
		
				$itsID = $itsID + 1;
	    	
				$startDate = date("Y-m-d h:i:s");

				$queryInfo = "INSERT INTO ITS_Inventory(its_id, client_id, part_number, part_name, bin_location, QTY, QTY_recorded, date_entered, variance, incoming_way_bill, serial_number, comments)VALUES(:its_id, :client_id, :part_number, :part_name, :bin_location, :QTY, :QTY_recorded, :date_entered, :variance, :incoming_way_bill, :serial_number, :comments)";

				$this->conn->beginTransaction();
				$statement = $this->conn->prepare($queryInfo);
				$statement->execute(array(
					"its_id" => $itsID,
					"client_id" => $clientID,
	        		"part_number" => $item,
					"part_name" => $descript,
					"bin_location" => $binLocation,
					"QTY" => $QTY,
					"QTY_recorded" => $QTY,
					"date_entered" => $startDate,
					"variance" => $variance,
					"incoming_way_bill" => $incomingWayBill,
					"serial_number" => $serialNumber,
					"comments" => $comments
				));

				//insert into receiving
				$lastId = $this->conn->lastInsertId();

				$queryInfo2 = "INSERT INTO ITS_Receiving(its_id, client_id, part_number, part_name, bin_location, QTY, QTY_recorded, variance, incoming_way_bill, received_date, serial_number, comments)VALUES(:its_id, :client_id, :part_number, :part_name, :bin_location, :QTY, :QTY_recorded, :variance, :incoming_way_bill, :received_date, :serial_number, :comments)";

				$statement2 = $this->conn->prepare($queryInfo2);
				$statement2->execute(array(
					"its_id" => $lastId,
					"client_id" => $clientID,
	        		"part_number" => $item,
					"part_name" => $descript,
					"bin_location" => $binLocation,
					"QTY" => $QTY,
					"QTY_recorded" => $QTY,
					"variance" => $variance,
					"incoming_way_bill" => $incomingWayBill,
					"received_date" => $startDate,
					"serial_number" => $serialNumber,
					"comments" => $comments
				));

				$this->conn->commit();
	      //return true;

			}catch(PDOException $e){
				$this->conn->rollback();
				//return "error";
			}
		}
  }
/* CONTROLS */



/**
	* Update Inventory Edits
	* @param $itsID - the inventory's key in the DB
	* @param $dataSet - an array of passed values to be edited
	* @param $dbTypeOld - The table the inventory is current statused in
	* @param descript - updates the user record data
	*
	*/
	public function updateInventory($ID, $itsID, $dataSet, $dbcurr, $moving = null)
	{
		try{

			//gets master data of item
			$toCompare = $this->getInventory(null, $itsID, null);
			$toCompare = json_decode($toCompare, true);
			$toCompare = $toCompare[0];
			
			$totalInventoryQTY = $toCompare['QTY'];
			$totalInventoryQTYrecorded = $toCompare['QTY_recorded'];
			$totalInventoryQTYordered = $toCompare['QTY_ordered'];
			$totalInventoryQTYaging = $toCompare['QTY_aging'];

			//creates new dataset for comparing
			$masterDataSet = array();
			$newDataSet = array();
			
			if(isset($dataSet['carrier'])){ $newCarrier=$dataSet['carrier']; }else{ $newCarrier=""; }
			if(isset($dataSet['carrier_tracking'])){ $newCarrierTracking=$dataSet['carrier_tracking']; }else{ $newCarrierTracking=""; }
			if(isset($dataSet['project_reference'])){ $newProjectRef=$dataSet['project_reference']; }else{ $newProjectRef=""; }
			if(isset($dataSet['shipping_date'])){ $newShippingDate=$dataSet['shipping_date']; }else{ $newShippingDate=""; }
			if(isset($dataSet['completed'])){ $newCompleted=$dataSet['completed']; }else{ $newCompleted=""; }
			
			unset($dataSet['carrier']);
			unset($dataSet['carrier_tracking']);
			unset($dataSet['project_reference']);
			unset($dataSet['shipping_date']);
			unset($dataSet['completed']);
			
			foreach($dataSet as $index => $key){
				if($index >= 5&&$key['name']!="newType"){
					$newDataSet[$key['name']] = trim((string)$key['value']);
					
					if(in_Array($key['name'],$toCompare)){
						$masterDataSet[$key['name']] = trim((string)$toCompare[$key['name']]);
					}
				}
			}
			

			//find the difference (full inventory || single)
			if($moving!=null){
				$result = array_diff_assoc($newDataSet, $toCompare);
				foreach($result as $index => $key){
					$toCompare[$index] = $key;
				}
				$result = $toCompare;
				$minus = 0;
			}else{
				$result = array_diff_assoc($newDataSet, $masterDataSet);
				$minus = 0;
			}


			if(isset($result['QTY_ordered'])||isset($result['QTY_aging'])||isset($result['QTY'])){
				if(isset($result['QTY_ordered'])&&$moving=="Shipping"){
					$minus = $result['QTY_ordered'];
				}elseif(isset($result['QTY_aging'])&&$moving=="Aging"){
					$minus = $result['QTY_aging'];
				}elseif(isset($result['QTY'])&&$moving=="Receiving"){
					$minus = $result['QTY'];
				}else{
					$minus = 0;
				}
			}
			
			$resultMaster = $result;
			

			/* SET MASTER COLS */
			$master = "";
			$arrayCount=0;
			foreach($result as $key => $value){
				if($arrayCount==0){
					$master .= ''.$key.'=:'.$key;
				}else{
					$master .= ','.$key.'=:'.$key;
				}
				$arrayCount++;
			}
			
			unset($resultMaster['carrier']);
			unset($resultMaster['carrier_tracking']);
			unset($resultMaster['project_reference']);
			unset($resultMaster['shipping_date']);
			unset($resultMaster['completed']);
			
			$masterSet = "";
			$arrayCount=0;
			foreach($resultMaster as $key => $value){
				if($arrayCount==0){
					$masterSet .= ''.$key.'=:'.$key;
				}else{
					$masterSet .= ','.$key.'=:'.$key;
				}
				$arrayCount++;
			}


			/* UPDATE MASTER & CURRENT TABLE if SIMPLE UPDATE */

			if($moving=="Receiving"){
				$result['QTY'] = $totalInventoryQTY + $result['QTY'];
			}elseif($moving=="Shipping"){
				$result['QTY_ordered'] = $totalInventoryQTYordered + $result['QTY_ordered'];
			}elseif($moving=="Aging"){
				$result['QTY_aging'] = $totalInventoryQTYaging + $result['QTY_aging'];
			}
			
			
			//STATIC EDIT
			if($moving==null){
				$currTable = "ITS_".$dbcurr."";
				$queryInfo = "UPDATE $currTable SET $master WHERE its_id = :itsID && id =:ID";
				$queryInfoMASTER = "UPDATE ITS_Inventory SET $masterSet WHERE its_id = :itsID";
				$statement = $this->conn->prepare($queryInfo);
				$statementMASTER = $this->conn->prepare($queryInfoMASTER);
				
					foreach($result as $key => $value){
						$statement->bindValue(":$key", $value);
					}	
					foreach($resultMaster as $key => $value){
						$statementMASTER->bindValue(":$key", $value);
					}	
					
				$statementMASTER->bindParam(":itsID", $itsID, PDO::PARAM_INT);
				$statement->bindParam(":itsID", $itsID, PDO::PARAM_INT);
				$statementMASTER->execute();
				$statement->bindParam(":ID", $ID, PDO::PARAM_INT);
 
				if($dbcurr!="On-Hand"){
					$updateCurr = $statement->execute();
				}
			}else{
				$updateQ = array();
					//QTY ADJUSTMENTS
					if($moving=="Receiving"){
						$result['QTY'] = $totalInventoryQTY - $newDataSet['QTY'];
						
						$updateQ['QTY'] = $result['QTY'];
						
						if($updateQ['QTY']<=0){ $updateQ['QTY']=0; }
					}elseif($moving=="Shipping"){
						$result['QTY'] = $result['QTY'] - ($totalInventoryQTYordered + $newDataSet['QTY_ordered']);
						$result['QTY_ordered'] = $totalInventoryQTYordered + $newDataSet['QTY_ordered'];
						$result['QTY_shipped'] = $totalInventoryQTYordered + $newDataSet['QTY_shipped'];
						
						$updateQ['QTY'] = $result['QTY'];
						$updateQ['QTY_ordered'] = $result['QTY_ordered'];
						$updateQ['QTY_shipped'] = $result['QTY_shipped'];
						
						if($updateQ['QTY']<=0){ $updateQ['QTY']=0; }
						if($updateQ['QTY_ordered']<=0){ $updateQ['QTY_ordered']=0; }
						if($updateQ['QTY_shipped']<=0){ $updateQ['QTY_shipped']=0; }
						
					}elseif($moving=="Aging"){
						$result['QTY'] = $result['QTY'] - ($totalInventoryQTYaging + $newDataSet['QTY_aging']);
						$result['QTY_aging'] = $totalInventoryQTYaging + $newDataSet['QTY_aging'];
					
						$updateQ['QTY'] = $result['QTY'];
						$updateQ['QTY_aging'] = $result['QTY_aging'];
						
						if($updateQ['QTY']<=0){ $updateQ['QTY']=0; }
						if($updateQ['QTY_aging']<=0){ $updateQ['QTY_aging']=0; }
					}
													
					$masterSet="";
					$arrayCount=0;
					foreach($updateQ as $key => $value){
						if($arrayCount==0){
							$masterSet .= ''.$key.'=:'.$key;
						}else{
							$masterSet .= ','.$key.'=:'.$key;
						}
						$arrayCount++;
					}
					
			
				//Master table
				$queryInfoMASTER = "UPDATE ITS_Inventory SET $masterSet WHERE its_id = :itsID";
				$statementMASTER = $this->conn->prepare($queryInfoMASTER);
					foreach($updateQ as $key => $value){
						$statementMASTER->bindValue(":$key", $value);
					}
				$statementMASTER->bindParam(":itsID", $itsID, PDO::PARAM_INT);
				$statementMASTER->execute();

				
				//sets the QTY update for the respective table
				if($dbcurr=="Shipping"){
					$masterSet="QTY_ordered=:QTY_ordered,QTY_shipped=:QTY_shipped";
				}elseif($dbcurr=="Aging"){
					$masterSet="QTY_aging=:QTY_aging";
				}else{
					$masterSet="QTY=:QTY";
				}
				
				
				//Current table
				$currTable = "ITS_".$dbcurr."";
				$queryInfo = "UPDATE $currTable SET $masterSet WHERE its_id = :itsID && ID = :ID";
				$statement = $this->conn->prepare($queryInfo);
				
				if($dbcurr=="Shipping"){
					$statement->bindValue(":QTY_ordered", $updateQ['QTY_ordered']);
					$statement->bindValue(":QTY_shipped", $updateQ['QTY_shipped']);
				}elseif($dbcurr=="Aging"){
					$statement->bindValue(":QTY_aging", $updateQ['QTY_aging']);
				}else{
					$statement->bindValue(":QTY", $updateQ['QTY']);
				}

				$statement->bindParam(":itsID", $itsID, PDO::PARAM_INT);
				$statement->bindParam(":ID", $ID, PDO::PARAM_INT);
				
				if($dbcurr!="On-Hand"){
					$updateCurr = $statement->execute();
				}
			}
			
				if($dbcurr == "Shipping"){
					$delQTY = $result['QTY_ordered'];
				}elseif($dbcurr == "Aging"){
					$delQTY = $result['QTY_aging'];
				}else{
					$delQTY = $result['QTY'];
				}
			
				//Delete record
				$del = $this->deleteInventoryItem($ID, $itsID, $dbcurr, null, $delQTY);
			echo true;
		}catch(PDOException $e){
			echo $e->getMessage();
			echo false;
		}
	}

	/**
		* Update Inventory edits & Movement of status
		* @param $itsID - the inventory's key in the DB
		* @param $dataSet - an array of passed values to be edited
		* @param $dbTypeOld - The table the inventory is current statused in
		* @param $dbTypeNew - The table the inventory is being moved to status in
		* @param descript - updates the user record data
		*
		*/
	public function updateMovingInventory($ID, $itsID, $dataSet, $dbcurr, $dbnew)
	{
		try{
			$update = $this->updateInventory($ID, $itsID, $dataSet, $dbcurr, $dbnew);
			$this->conn->beginTransaction();

			//creates new dataset for comparing
			$newDataSet = array();
			foreach($dataSet as $index => $key){
				if($index >= 4){
					$newDataSet[$key['name']] = $key['value'];
				}
			}


			/* TOSS VALUES FOR TABLES */
			unset($newDataSet['newType']);
			if($dbnew=="Receiving"){
			$tossForShipping = array('QTY_shipped','carrier','carrier_tracking','project_reference','shipping_date');
				foreach($newDataSet as $key => $value){
					if(in_array($key, $tossForShipping)){
						unset($newDataSet[$key]);
					}
				}
				//adjustments
				if($dbcurr=="Shipping"){
					$newDataSet['QTY'] = $newDataSet['QTY_ordered'];
					unset($newDataSet['QTY_ordered']);
				}elseif($dbcurr=="Aging"){
					$newDataSet['QTY'] = $newDataSet['QTY_aging'];
					unset($newDataSet['QTY_aging']);
				}
			}
			if($dbnew=="Shipping"){
			$tossForShipping = array('QTY','received_date','bin_location','QTY_recorded','variance','comments');
				foreach($newDataSet as $key => $value){
					if(in_array($key, $tossForShipping)){
						unset($newDataSet[$key]);
					}
				}
			}
			if($dbnew=="Aging"){
			$tossForShipping = array('incoming_way_bill','bin_location','serial_number','QTY','QTY_recorded','variance','comments');
				foreach($newDataSet as $key => $value){
					if(in_array($key, $tossForShipping)){
						unset($newDataSet[$key]);
					}
				}
			}

			unset($newDataSet['id']);
			/* SET MASTER COLS */
			$masterVal = "";
			$masterValSet = "";
			$arrayCount=0;
			foreach($newDataSet as $key => $value){
				if($arrayCount==0){
					$masterVal .= ''.$key;
					$masterValSet .= ':'.$key;
				}else{
					$masterVal .= ','.$key;
					$masterValSet .= ',:'.$key;
				}
				$arrayCount++;
			}


			$dbnew = "ITS_".$dbnew;
			$queryInfo2 = "INSERT INTO $dbnew($masterVal)VALUES($masterValSet)";

			$statement2 = $this->conn->prepare($queryInfo2);
			$statement2->execute($newDataSet);

			$this->conn->commit();

			echo true;
		}catch(PDOException $e){
			$this->conn->rollback();
			echo $e->getMessage();
			echo false;
		}
	}

	/**
		* inventory item Delete
		* @param $itsID - the inventory item key in the DB
		* @param $dbCurr - the current status DB of the inventory item
		* @param descript - deletes a inventory item record from the system
		*
		*/
	public function deleteInventoryItem($ID, $itsID, $dbCurr, $manually = null, $qty)
	{
		try{
			$QTY=$qty;
			$multiTableCheck = $this->checkMasterQTY($itsID, $dbCurr, $QTY);
			
			/*echo $QTY.'\n';
			echo $dbCurr.'\n';
			echo $manually.'\n';
			echo $ID.'\n';
			echo $dbCurr.'\n';*/
			
			if($dbCurr=="On-Hand"){
				$dbCurr = "ITS_Inventory";
			}else{
				$dbCurr = "ITS_".$dbCurr;			
			}
			
			
			if($dbCurr=="ITS_Shipping"){
				$col = "QTY_ordered";
			}elseif($dbCurr=="ITS_Aging"){
				$col = "QTY_aging";
			}else{
				$col = "QTY";
			}
			//UPDATE CURRENT SITCH
			//if manually delete or item is moving
			if($manually!=null){
				//update (partial delete) || delete (full qty change)
				if($multiTableCheck==1){
					if($dbCurr=="ITS_Inventory"){
						$queryInfoRECEIVING = "DELETE FROM ITS_Receiving WHERE its_id = :itsID && id = :ID";
						$itemRECEIVING = $this->conn->prepare($queryInfoRECEIVING);
						$itemRECEIVING->bindParam(":ID", $ID, PDO::PARAM_INT);
						$itemRECEIVING->bindParam(":itsID", $itsID, PDO::PARAM_INT);
						$itemRECEIVING->setFetchMode(PDO::FETCH_ASSOC);
						$itemRECEIVING->execute();
					}else{
						if((int)$QTY<1){
							$queryInfo = "DELETE FROM $dbCurr WHERE its_id = :itsID && id = :ID";
							$item = $this->conn->prepare($queryInfo);
							$item->bindParam(":itsID", $itsID, PDO::PARAM_INT);
							$item->bindParam(":ID", $ID, PDO::PARAM_INT);
							$item->setFetchMode(PDO::FETCH_ASSOC);
							$item->execute();
						}else{
							$queryInfo = "DELETE FROM $dbCurr WHERE its_id = :itsID && id = :ID";
							$item = $this->conn->prepare($queryInfo);
							$item->bindParam(":itsID", $itsID, PDO::PARAM_INT);
							$item->bindParam(":ID", $ID, PDO::PARAM_INT);
							$item->setFetchMode(PDO::FETCH_ASSOC);
							$item->execute();
						}
					}
				}else{		
					if($dbCurr=="ITS_Inventory"){
						$queryInfoRECEIVING = "UPDATE ITS_Receiving SET QTY = QTY - :QTY WHERE its_id = :itsID && id = :ID";
						$itemRECEIVING = $this->conn->prepare($queryInfoRECEIVING);
						$itemRECEIVING->bindParam(":itsID", $itsID, PDO::PARAM_INT);
						$itemRECEIVING->bindParam(":QTY", $QTY, PDO::PARAM_INT);
						$itemRECEIVING->bindParam(":ID", $ID, PDO::PARAM_INT);
						$itemRECEIVING->setFetchMode(PDO::FETCH_ASSOC);
						$itemRECEIVING->execute();
					}else{
						$queryInfo = "SELECT * FROM ITS_Inventory WHERE its_id = :its_id ORDER BY its_id DESC LIMIT 1";
						$inventory = $this->conn->prepare($queryInfo);
						$inventory->bindParam(":its_id", $itsID, PDO::PARAM_INT);
						$inventory->setFetchMode(PDO::FETCH_ASSOC);
						$inventory->execute();
						$inventory = $inventory->fetchAll();

						if(empty($inventory)){ return; }

						$chkList= array();
						
						
						$chkList['QTY'] = $inventory[0]['QTY'];
						$chkList['QTY_recorded'] = $inventory[0]['QTY_recorded'];
						$chkList['QTY_discarded'] = $inventory[0]['QTY_discarded'];
						$chkList['QTY_aging'] = $inventory[0]['QTY_aging'];
						$chkList['QTY_ordered'] = $inventory[0]['QTY_ordered'];
						$chkList['QTY_shipped'] = $inventory[0]['QTY_shipped'];		
					
						if($chkList[$col]==$QTY){
							$QTY = 0;
						}		
					
						if($QTY<1){
							$queryInfo = "DELETE FROM $dbCurr WHERE its_id = :itsID && id = :ID";
							$item = $this->conn->prepare($queryInfo);
							$item->bindParam(":itsID", $itsID, PDO::PARAM_INT);
							$item->bindParam(":ID", $ID, PDO::PARAM_INT);
							$item->setFetchMode(PDO::FETCH_ASSOC);
							$item->execute();
						}else{
							$queryInfoUpdate = "UPDATE $dbCurr SET $col = $col - :QTY WHERE its_id = :itsID && id = :ID";
							$statement = $this->conn->prepare($queryInfoUpdate);
							$statement->bindParam(":itsID", $itsID, PDO::PARAM_INT);
							$statement->bindParam(":QTY", $QTY, PDO::PARAM_INT);
							$statement->bindParam(":ID", $ID, PDO::PARAM_INT);
							$statement->setFetchMode(PDO::FETCH_ASSOC);
							$statement->execute();
						}
					}
				}
		
			}else{
				if($multiTableCheck==1){
					if($dbCurr=="ITS_Inventory"){
						$queryInfoRECEIVING = "DELETE FROM ITS_Receiving WHERE its_id = :itsID && id = :ID";
						$itemRECEIVING = $this->conn->prepare($queryInfoRECEIVING);
						$itemRECEIVING->bindParam(":ID", $ID, PDO::PARAM_INT);
						$itemRECEIVING->bindParam(":itsID", $itsID, PDO::PARAM_INT);
						$itemRECEIVING->setFetchMode(PDO::FETCH_ASSOC);
						$itemRECEIVING->execute();
					}else{
						if($manually!=null){
							$queryInfo = "DELETE FROM $dbCurr WHERE its_id = :itsID && id = :ID";
							$item = $this->conn->prepare($queryInfo);
							$item->bindParam(":itsID", $itsID, PDO::PARAM_INT);
							$item->bindParam(":ID", $ID, PDO::PARAM_INT);
							$item->setFetchMode(PDO::FETCH_ASSOC);
							$item->execute();
						}elseif($QTY<1){
							$queryInfo = "DELETE FROM $dbCurr WHERE its_id = :itsID && id = :ID";
							$item = $this->conn->prepare($queryInfo);
							$item->bindParam(":itsID", $itsID, PDO::PARAM_INT);
							$item->bindParam(":ID", $ID, PDO::PARAM_INT);
							$item->setFetchMode(PDO::FETCH_ASSOC);
							$item->execute();
						}
					}
				}elseif($QTY<1){
					$queryInfo = "DELETE FROM $dbCurr WHERE its_id = :itsID && id = :ID";
					$item = $this->conn->prepare($queryInfo);
					$item->bindParam(":itsID", $itsID, PDO::PARAM_INT);
					$item->bindParam(":ID", $ID, PDO::PARAM_INT);
					$item->setFetchMode(PDO::FETCH_ASSOC);
					$item->execute();
				}
			}




			//UPDATE MASTER
			if($multiTableCheck==1){
				if($manually!=null){
					$queryInfoMASTER = "DELETE FROM ITS_Inventory WHERE its_id = :itsID";
					$itemMASTER = $this->conn->prepare($queryInfoMASTER);
					$itemMASTER->bindParam(":itsID", $itsID, PDO::PARAM_INT);
					$itemMASTER->setFetchMode(PDO::FETCH_ASSOC);
					$itemMASTER->execute();
				}elseif($QTY<1){
					$queryInfo = "DELETE FROM ITS_Inventory WHERE its_id = :itsID";
					$item = $this->conn->prepare($queryInfo);
					$item->bindParam(":itsID", $itsID, PDO::PARAM_INT);
					$item->setFetchMode(PDO::FETCH_ASSOC);
					$item->execute();
				}
			}else{
				if($manually!=null){	
					$queryInfo = "SELECT * FROM ITS_Inventory WHERE its_id = :its_id ORDER BY its_id DESC LIMIT 1";
					$inventory = $this->conn->prepare($queryInfo);
					$inventory->bindParam(":its_id", $itsID, PDO::PARAM_INT);
					$inventory->setFetchMode(PDO::FETCH_ASSOC);
					$inventory->execute();
					$inventory = $inventory->fetchAll();

					if(empty($inventory)){ return; }
					
					$chkList= array();
									
					$chkListQTY = $inventory[0]['QTY'];
					$chkListRecorded = $inventory[0]['QTY_recorded'];
					$chkList['QTY_discarded'] = $inventory[0]['QTY_discarded'];
					$chkList['QTY_aging'] = $inventory[0]['QTY_aging'];
					$chkList['QTY_ordered'] = $inventory[0]['QTY_ordered'];
	
	
					$summed = array_sum($chkList);
					
				
					if($chkListQTY==0&&$chkListRecorded==($summed+$QTY)){
						$queryInfo = "DELETE FROM ITS_Inventory WHERE its_id = :itsID";
						$item = $this->conn->prepare($queryInfo);
						$item->bindParam(":itsID", $itsID, PDO::PARAM_INT);
						$item->setFetchMode(PDO::FETCH_ASSOC);
						$item->execute();	
					}else{			
					
						if($chkListQTY<1){
							$QTYdiscarded = $QTY;
							$QTY = 0;
						}else{
							$QTYdiscarded = $QTY;
						}
	
					
						if($dbCurr=="ITS_Aging"){
							$queryInfoUpdate = "UPDATE ITS_Inventory SET QTY = QTY - :QTY, QTY_discarded = QTY_discarded + :QTYdiscarded, QTY_aging = QTY_aging - :QTYdiscarded WHERE its_id = :itsID";	
						}elseif($dbCurr=="ITS_Shipping"){
							$queryInfoUpdate = "UPDATE ITS_Inventory SET QTY = QTY - :QTY, QTY_discarded = QTY_discarded + :QTYdiscarded, QTY_ordered = QTY_ordered - :QTYdiscarded, QTY_shipped = QTY_shipped - :QTYdiscarded WHERE its_id = :itsID";
						}else{
							$queryInfoUpdate = "UPDATE ITS_Inventory SET QTY = QTY - :QTY, QTY_discarded = QTY_discarded + :QTYdiscarded WHERE its_id = :itsID";
						}
					
						$statement = $this->conn->prepare($queryInfoUpdate);
						$statement->bindParam(":itsID", $itsID, PDO::PARAM_INT);
						$statement->bindParam(":QTY", $QTY, PDO::PARAM_INT);
						$statement->bindParam(":QTYdiscarded", $QTYdiscarded, PDO::PARAM_INT);
						$statement->setFetchMode(PDO::FETCH_ASSOC);
						$statement->execute();
					}
				}
			}
			echo true;
		}catch(PDOException $e){
			echo false;
		}
	}
	
/*completed inventory*/
  public function markCompleted($ID, $itsID)
  {

    try{
			$completedDate = date("Y-m-d h:i:s");

			$queryInfo = "UPDATE ITS_Shipping SET completed = :completed WHERE id = :ID && its_id = :itsID";

			$this->conn->beginTransaction();
			$statement = $this->conn->prepare($queryInfo);
			$statement->bindValue(":completed", $completedDate);
			$statement->bindParam(":ID", $ID, PDO::PARAM_INT);
			$statement->bindParam(":itsID", $itsID, PDO::PARAM_INT);
			$statement->execute();

			$this->conn->commit();
      echo true;
      exit;

		}catch(PDOException $e){
			//echo $e->getMessage();
			$this->conn->rollback();
			echo false;
      exit;
		}
  }
}

?>
