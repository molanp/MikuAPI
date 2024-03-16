<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (tokentime($_GET)) {
        $data = [];
        $memoryLimit = ini_get('memory_limit');
        if (preg_match('/^(\d+)(.)$/', $memoryLimit, $matches)) {
            if ($matches[2] == 'M') {
                $memoryLimit = $matches[1];
            } elseif ($matches[2] == 'K') {
                $memoryLimit = $matches[1] / 1024;
            } elseif ($matches[2] == 'G') {
                $memoryLimit = $matches[1] * 1024;
            }
        }
        $memoryUsage = memory_get_peak_usage(true) / (1024 * 1024);
        $memoryUsagePercentage = ($memoryUsage / $memoryLimit) * 100;
        $data["memory"] = ["usage" => $memoryUsage, "max" => $memoryLimit, "percentage" => round($memoryUsagePercentage, 2)];

        $totalSpace = disk_total_space("/");
        $freeSpace = disk_free_space("/");
        $usedSpace = $totalSpace - $freeSpace;
        $usedPercentage = ($usedSpace / $totalSpace) * 100;
        $data["disk"] = ["usage" => $usedSpace / (1024 * 1024), "max" => $totalSpace / (1024 * 1024), "percentage" => round($usedPercentage, 2)];

        $data["arch"] = php_uname('m');
        $data["name"] = php_uname('s');
        $data["version"] = php_uname('r');
        $data["all"] = php_uname();
        _return_($data);
    } else {
        code(401);
    }
}
