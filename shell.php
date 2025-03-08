<?php
$l='logs.json';

function get_ip() {
    $json = file_get_contents('http://ip-api.com/json/');
    $ip_data = json_decode($json, true);
    return $ip_data['query'] ?? 'UNKNOWN';
}

function log_action($c, $o) {
    global $l;
    $e = [
        't' => date('Y-m-d H:i:s'),
        'c' => $c,
        'o' => $o,
        'ip' => get_ip()
    ];
    if (file_exists($l)) {
        $d = json_decode(file_get_contents($l), true);
    } else {
        $d = [];
    }
    $d[] = $e;
    file_put_contents($l, json_encode($d, JSON_PRETTY_PRINT));
}

function exec_cmd($c) {
    $f = [
        'index.html', 'about.html', 'contact.php', 'style.css', 'app.js', 'config.php', '.htaccess', 'error_log', 'access_log'
    ];
    $u = [
        'root', 'www-data', 'admin', 'user', 'guest'
    ];
    $p = [
        '1 init', '2 kthreadd', '3 ksoftirqd/0', '4 kworker/0:0H', '5 kworker/0:0', '100 apache2', '101 nginx', '102 php-fpm'
    ];

    switch ($c) {
        case 'ls':
            return implode("\n", $f) . "\n";
        case 'pwd':
            return "/var/www/html\n";
        case 'whoami':
            return "www-data\n";
        case 'ps aux':
            return "USER       PID %CPU %MEM    VSZ   RSS TTY      STAT START   TIME COMMAND\n" .
                   implode("\n", array_map(fn($p) => "root      $p    0.0  0.1  15820  2008 ?        Ss   Mar08   0:00 $p", $p)) . "\n";
        case 'cat /etc/passwd':
            return implode("\n", array_map(fn($u) => "$u:x:1000:1000::/home/$u:/bin/bash", $u)) . "\n";
        case 'df -h':
            return "Filesystem      Size  Used Avail Use% Mounted on\n" .
                   "/dev/sda1       100G   50G   50G  50% /\n" .
                   "tmpfs            64M     0   64M   0% /dev/shm\n";
        case 'top -bn1':
            return "top - 19:45:08 up  1:00,  1 user,  load average: 0.00, 0.01, 0.05\n" .
                   "Tasks:  10 total,   1 running,   9 sleeping,   0 stopped,   0 zombie\n" .
                   "%Cpu(s):  0.3 us,  0.1 sy,  0.0 ni, 99.6 id,  0.0 wa,  0.0 hi,  0.0 si,  0.0 st\n" .
                   "KiB Mem:   2048000 total,   500000 used,  1548000 free,    10000 buffers\n" .
                   "KiB Swap:  2048000 total,        0 used,  2048000 free.   200000 cached Mem\n" .
                   "\n" .
                   "  PID USER      PR  NI    VIRT    RES    SHR S  %CPU %MEM     TIME+ COMMAND\n" .
                   "    1 root      20   0   15820   2008   1812 S   0.0  0.1   0:00.01 init\n";
        case 'ifconfig':
            return "eth0      Link encap:Ethernet  HWaddr 00:0c:29:68:22:ef\n" .
                   "          inet addr:192.168.0.1  Bcast:192.168.0.255  Mask:255.255.255.0\n" .
                   "          inet6 addr: fe80::20c:29ff:fe68:22ef/64 Scope:Link\n" .
                   "          UP BROADCAST RUNNING MULTICAST  MTU:1500  Metric:1\n" .
                   "          RX packets:1000 errors:0 dropped:0 overruns:0 frame:0\n" .
                   "          TX packets:1000 errors:0 dropped:0 overruns:0 carrier:0\n" .
                   "          collisions:0 txqueuelen:1000 \n" .
                   "          RX bytes:100000 (100.0 KB)  TX bytes:100000 (100.0 KB)\n";
        case 'uname -a':
            return "Linux server 5.4.0-42-generic #46-Ubuntu SMP Fri Jul 10 00:24:02 UTC 2020 x86_64 x86_64 x86_64 GNU/Linux\n";
        case 'uptime':
            return " 19:45:08 up  1:00,  1 user,  load average: 0.00, 0.01, 0.05\n";
        case 'netstat -tuln':
            return "Proto Recv-Q Send-Q Local Address           Foreign Address         State       \n" .
                   "tcp        0      0 0.0.0.0:80              0.0.0.0:*               LISTEN      \n" .
                   "tcp        0      0 0.0.0.0:22              0.0.0.0:*               LISTEN      \n" .
                   "udp        0      0 0.0.0.0:123             0.0.0.0:*                           \n";
        case 'history':
            return "    1  ls\n    2  pwd\n    3  whoami\n    4  ps aux\n    5  cat /etc/passwd\n";
        default:
            return "command not found: $c\n";
    }
}

if (isset($_POST['command'])) {
    $c = $_POST['command'];
    $o = exec_cmd($c);
    log_action($c, $o);
    echo nl2br(htmlspecialchars($o));
} else {
    echo '<form method="post">';
    echo '<input type="text" name="command" placeholder="Enter command">';
    echo '<input type="submit" value="Execute">';
    echo '</form>';
}
?>
