<?php

include "lib/ICS.php";

const isDebugMode = false;

if (!isDebugMode) {
  header("Content-Type: text/calendar; charset=UTF-8");
  header("Content-Disposition: attachment; filename=event.ics");
}

$ics_array = [];

$ics_array["timezone"] = get_option("timezone_string");

if ($_GET["location"]) {
  $ics_array["location"] = html_entity_decode($_GET["location"]);
}

if ($_GET["description"]) {
  $ics_array["description"] = html_entity_decode($_GET["description"]);
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
  $ics_array["summary"] = html_entity_decode($_GET["summary"]);

}
if ($_GET["url"]) {
  $ics_array["url"] = $_GET["url"];
}

$ics = new ICS($ics_array);

if (isDebugMode) {
  echo "<pre>";
}

echo $ics->to_string();

if (isDebugMode) {
  echo "</pre>";
}
