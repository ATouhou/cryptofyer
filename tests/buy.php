<?php
  include("includes.php");

  $price  = isSet($_GET["rate"]) ? strtoupper($_GET["rate"]) : 0;
  $amount = isSet($_GET["amount"]) ? strtoupper($_GET["amount"]) : 0;

  echo "<form method='get'>";
  echo "<table border='1' cellpadding='5'  cellspacing='0'>";
  echo "<tr>";
  echo "<td><strong>Exchange</strong></td>";
  echo "<td><strong>Market</strong></td>";
  echo "<td><strong>Currency</strong></td>";
  echo "<td><strong>Rate</strong></td>";
  echo "<td><strong>Amount</strong></td>";
  echo "<td></td>";
  echo "</tr>";

  echo "<tr>";
  echo "<td>";
  echo "<select name='exchange'>";
  foreach($config as $key=>$value) {
    $selected = $key==$exchange ? "SELECTED" : "";
    echo "<option value='" . $key ."' " . $selected . ">" .$key . "</option>";
  }
  echo "</select>";
  echo "</td>";
  echo "<td><input type='text' name='market' value='" . $_market . "'></td>";
  echo "<td><input type='text' name='currency' value='" . $_currency . "'></td>";
  echo "<td><input type='text' name='rate' value='" . $price . "'></td>";
  echo "<td><input type='text' name='amount' value='" . $amount . "'></td>";
  echo "<td><input type='submit' value='send'></td>";
  echo "</tr>";

  echo "</table>";
  echo "</form>";


  if(empty($exchange)) die("no exchange found!");
  $exchangeName = strtolower(trim($exchange));
  if(!isSet($config) || !isSet($exchangesInstances[$exchangeName])) die("no config for ". $exchangeName ." found!");

  $exchange = $exchangesInstances[$exchangeName];
  if(empty($exchange)) die("cannot init exchange " . $exchangeName);
  
  echo "api version : " . $exchange->getVersion() . "<br>";

  $market     = $exchange->getMarketPair($_market,$_currency);

  echo "<h1>Method: buy()</h1>";

  echo "Exchange: " . $exchangeName . "<br>";
  echo "Market: <a href='" . $exchange->getCurrencyUrl(array("_market" => $_market,"_currency"=>$_currency)) . "' target='_blank'>" . $market . "</a><br>";


  $buyOBJ  = $exchange->buy(array("_market" => $_market , "_currency" => $_currency , "rate" => $price , "amount" => $amount));
  debug($buyOBJ);

  echo "<h1>Method: getOrder()</h1>";
  if($buyOBJ["success"] == true) {
    $orderOBJ = $exchange->getOrder(array("orderid" => $buyOBJ["result"]["orderid"]));
    debug($orderOBJ);
  }
 ?>
