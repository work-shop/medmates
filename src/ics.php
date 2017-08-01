<?php

include "lib/ICS.php";

header("Content-Type: text/calendar; charset=UTF-8");
header("Content-Disposition: attachment; filename=event.ics");

$ics_array = [];

if ($_GET["location"]) {
  $ics_array["location"] = $_GET["location"];
}

if ($_GET["description"]) {
  $ics_array["description"] = $_GET["description"];
}

if ($_GET["date_start"]) {
  $ics_array["dtstart"] = $_GET["date_start"];

  if ($_GET["date_end"]) {
    $ics_array["dtend"] = $_GET["date_end"];
  } else {
    $dt = new DateTime($_GET["date_start"]);
    $dt->modify("+ 1 hour");
    $ics_array["dtend"] = $dt->format("Y-m-d H:i:s");
  }
}

if ($_GET["summary"]) {
  $ics_array["summary"] = $_GET["summary"];

}
if ($_GET["url"]) {
  $ics_array["url"] = $_GET["url"];
}

$ics = new ICS($ics_array);

echo $ics->to_string();
