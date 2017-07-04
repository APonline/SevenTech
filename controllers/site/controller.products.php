<?php

require_once("inc/config.php");
require_once("model/class.inventory.php");
require_once("model/class.client.php");

$msg = "";
$pagePost = \Fr\LS::curPageURL();

$inventoryType = "Receiving";
$emptyTable=0;
$selected = 0;
$tableSet = "";
$clientFilter = "";

if(isset($_POST['userClient'])){
  $selected = $_POST['userClient'];
}

$details = \Fr\LS::getUser();
if($details['user_type']>=1 || $details['client_id']==1){

  $parentCO = $details['client_id'];

  if($details['user_type']==1){
    $client = new Client();
    $clientInfo = $client->getAllSubClientInfo($parentCO);
    $clientInfo = json_decode($clientInfo, true);

  }else{
    /*client search*/
    $client = new Client();
    $clientInfo = $client->getAllClientInfo();
    $clientInfo = json_decode($clientInfo, true);
  }

  $clientOrganizationSelect = "";
  foreach ($clientInfo as $key => $value) {
    if($value['active'] == "1"){
      if($selected==$value['client_id']){
        $clientOrganizationSelect .= '
          <option name="userClient" data-id=" ' . $value['client_admin'] . '" value=" ' . $value['client_id'] . '" selected> ' . $value['name'] .'</option>
        ';
      }else{
        $clientOrganizationSelect .= '
          <option name="userClient" data-id=" ' . $value['client_admin'] . '" value=" ' . $value['client_id'] . '"> ' . $value['name'] .'</option>
        ';
      }
    }
  }

  $clientFilter = "
    <form action=\"$pagePost\" id=\"clientFilterForm\" class=\"form-group form-inline clientFormFilter\" method='POST'>
        <label>Client</label>
        <select class=\"form-control input-sm userClient\" id=\"userClient\" name=\"userClient\">
           <option value=\"0\">All Clients</option>
            $clientOrganizationSelect
        </select>
    </form>
  ";
}

//if a client or employee force select to be their client id
if($details['user_type']<1 && $details['client_id']!=1){
  $selected = $details['client_id'];
}

$inventorySet = new Inventory();
$inventorySet = $inventorySet->getInventoryReceiving($selected);
$inventorySet = json_decode($inventorySet, true);

$clientSet = new Client();
$clientSet = $clientSet->getClientInfo($selected);
$clientSet = json_decode($clientSet, true);

$tableSet =" $clientFilter
<table class=\"col-md-12 table-bordered table-striped table-condensed cf table-hover table-filters\">
";
  if($inventorySet==null){
    $emptyTable=1;
  }else{

    $tableSet .="
    <thead class=\"cf\">
      <tr>
    ";

      foreach ($inventorySet[0] as $k => $v) {
        if($k=="its_id"||$k=="client_id"||$k=="id"){
          $tableSet .='
            <th class="itsID">'.str_replace('_', ' ', $k).'</th>
          ';
        }else{
          $tableSet .='
            <th>'.ucfirst(str_replace('_', ' ', $k)).'</th>
          ';
        }
      }

    $tableSet .="
      </tr>
    </thead>
    <tbody>
    ";

    foreach ($inventorySet as $k => $v) {
      $tableSet .="<tr>";
      foreach ($v as $k2 => $v2) {
        if($k2=="its_id"||$k2=="client_id"||$k2=="id"){
          $tableSet .='
            <td class="itsID" data-title="'.$k2.'">'.$v2.'</td>
          ';
        }else{
          if($k2=="comments"){
            $v3 = strlen($v2) > 50 ? substr($v2,0,50)."..." : $v2;

            if($v3 == ""){

              $v3 = "No comments for this item.";

            }

            $tableSet .='<td data-title="'.$k2.'" data-comment="'.$v2.'" class="clickable-row commentView">'.$v3.'</td>';
          }else{
            $tableSet .='<td data-title="'.$k2.'">'.$v2.'</td>';
          }
        }
      }
      $tableSet .="</tr>";
    }
  }
$tableSet .="
  </tbody>
  </table>
";

if($emptyTable==1){

  if(isset($_POST['userClient'])){

    $selected = $_POST['userClient'];

    $breakLine = "<br>";

    if($selected == "0") {

        $clientFilter = "";

        $breakLine = "<br/>";
    }

  }else{

    $clientFilter = "";

    $breakLine = "";

  }

  $tableSet =  "$clientFilter <div class=\"col-md-12 noDataLabel\">$breakLine<p>No Data Available.</p></div>";
}

?>
