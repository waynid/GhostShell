<div align="center">

# GhostShell

GhostShell is a fake shell honeypot that simulates a real shell environment to deceive attackers. It logs all actions into `logs.json`.

</div>

## Features

- Realistic shell command outputs.
- Logs all commands, outputs, timestamps, and IP addresses.

## Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/waynid/GhostShell.git
    ```

2. Navigate to the project directory:
    ```sh
    cd GhostShell
    ```

3. Place `shell.php` on your web server's root directory (e.g., `/var/www/html/` for Apache).

4. Set write permissions for `logs.json`:
    ```sh
    chmod 666 logs.json
    ```

## Usage

1. Open a browser and go to the URL hosting `shell.php` (e.g., `http://yoursexysecurewebsite.net/shell.php`).

2. Enter a shell command and click "Execute".

<details>
<summary>📁 Example Commands</summary>

- `ls`
- `pwd`
- `whoami`
- `ps aux`
- `cat /etc/passwd`
- `df -h`
- `top -bn1`
- `ifconfig`
- `uname -a`
- `uptime`
- `netstat -tuln`
- `history`

</details>

## Logging

All commands are logged in `logs.json` with:
- `timestamp`
- `command`
- `output`
- `ip_address`

## Contributing

Fork the repository and create a pull request with your changes.

## Contact

For questions, send issues. [waynid](https://github.com/waynid).
