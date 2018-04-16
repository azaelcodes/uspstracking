<?php
use AzaelCodes\Utils\USPSPackageTracker;

$tracker = new USPSPackageTracker('9400136895357348963746');
echo $tracker->getStatus();