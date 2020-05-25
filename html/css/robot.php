<?php
header("Content-type: text/css");
$S1 = "S1";
?>

#toggle_names {
  margin-top:20px;
  margin-left:20px;
  text-align:left;
}
.robot {
  background: var(--bg-color);
}

.robot.<?=$S1 ?>{
  width: 20px;
  height: 30px;
  position: absolute;
  top: calc(41% + var(--pos) );
  left: 4%;
}

.robot_hover.<?=$S1 ?> {
  position: absolute;
  top: 7px;
  left: 30px;
  width: 110px;
  font-weight: bold;
  text-align: left;
}

.robot:hover .robot_hover.<?=$S1 ?> {
    display: block;
}

.robot.parking {
  width: 3%;
  height: 5%;
  position: absolute;
  top: 88%;
  left: calc(30% + var(--pos));
  transform: rotate(90deg);
}

.robot_hover.parking {
  position: absolute;
  top: -34px;
  text-align: left;
  left: -87px;
  width: 110px;
  transform: rotate(-120deg);
  font-weight: bold;
}

.robot:hover .robot_hover.parking {
    display: block !important;
}
