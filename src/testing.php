<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 6/12/14
 * Time: 13:28
 */

$dateTime = DateTime::createFromFormat('d/m/Y', '06/12/2014');
$dbFormat = $dateTime->format('Y-m-d');
echo $dbFormat;
$myFormat = DateTime::createFromFormat('Y-m-d', $dbFormat);
echo "<br>" . $myFormat->format('d/m/Y');
