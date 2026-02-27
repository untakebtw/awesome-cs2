<?php
/**
 * Source Engine Server Query Protocol
 * Queries CS2/CSGO servers for info (A2S_INFO)
 * 
 * Author: untakebtw
 * GitHub: https://github.com/untakebtw
 * Discord: https://discord.gg/SdjmNnp56N
 */

class SourceQuery {
    private $ip;
    private $port;
    private $timeout;

    public function __construct($ip, $port, $timeout = 3) {
        $this->ip = $ip;
        $this->port = (int)$port;
        $this->timeout = $timeout;
    }

    /**
     * Query server info using A2S_INFO protocol
     * @return array|false Server info or false on failure
     */
    public function getInfo() {
        $socket = @fsockopen('udp://' . $this->ip, $this->port, $errno, $errstr, $this->timeout);
        if (!$socket) {
            return false;
        }

        stream_set_timeout($socket, $this->timeout);

        // A2S_INFO request: 4 bytes header (0xFF 0xFF 0xFF 0xFF) + 'T' + "Source Engine Query\0"
        $request = "\xFF\xFF\xFF\xFF\x54Source Engine Query\x00";
        fwrite($socket, $request);

        $response = fread($socket, 4096);
        fclose($socket);

        if (empty($response)) {
            return false;
        }

        // Check for challenge response (S2C_CHALLENGE)
        if (strlen($response) >= 9 && ord($response[4]) === 0x41) {
            // Need to resend with challenge number
            $challenge = substr($response, 5, 4);
            $socket = @fsockopen('udp://' . $this->ip, $this->port, $errno, $errstr, $this->timeout);
            if (!$socket) return false;
            stream_set_timeout($socket, $this->timeout);
            fwrite($socket, $request . $challenge);
            $response = fread($socket, 4096);
            fclose($socket);
            if (empty($response)) return false;
        }

        return $this->parseInfoResponse($response);
    }

    /**
     * Parse A2S_INFO response
     */
    private function parseInfoResponse($data) {
        if (strlen($data) < 6) return false;

        $pos = 4; // Skip 0xFFFFFFFF header
        $header = ord($data[$pos]);
        $pos++;

        if ($header === 0x49) {
            // Source engine response
            return $this->parseSourceInfo($data, $pos);
        }

        return false;
    }

    private function parseSourceInfo($data, $pos) {
        $info = [];

        // Protocol
        $info['protocol'] = ord($data[$pos]);
        $pos++;

        // Server name (null-terminated string)
        $info['hostname'] = $this->readString($data, $pos);

        // Map
        $info['map'] = $this->readString($data, $pos);

        // Game directory
        $info['gamedir'] = $this->readString($data, $pos);

        // Game description
        $info['gamedesc'] = $this->readString($data, $pos);

        // App ID (2 bytes, little-endian)
        if ($pos + 2 <= strlen($data)) {
            $info['appid'] = unpack('v', substr($data, $pos, 2))[1];
            $pos += 2;
        }

        // Number of players
        if ($pos < strlen($data)) {
            $info['players'] = ord($data[$pos]);
            $pos++;
        }

        // Max players
        if ($pos < strlen($data)) {
            $info['maxplayers'] = ord($data[$pos]);
            $pos++;
        }

        // Number of bots
        if ($pos < strlen($data)) {
            $info['bots'] = ord($data[$pos]);
            $pos++;
        }

        // Server type
        if ($pos < strlen($data)) {
            $info['servertype'] = chr(ord($data[$pos]));
            $pos++;
        }

        // Environment (OS)
        if ($pos < strlen($data)) {
            $info['os'] = chr(ord($data[$pos]));
            $pos++;
        }

        // Visibility (password)
        if ($pos < strlen($data)) {
            $info['password'] = ord($data[$pos]);
            $pos++;
        }

        // VAC
        if ($pos < strlen($data)) {
            $info['vac'] = ord($data[$pos]);
            $pos++;
        }

        return $info;
    }

    /**
     * Read null-terminated string from data
     */
    private function readString(&$data, &$pos) {
        $str = '';
        $len = strlen($data);
        while ($pos < $len && $data[$pos] !== "\x00") {
            $str .= $data[$pos];
            $pos++;
        }
        $pos++; // Skip null terminator
        return $str;
    }
}

/**
 * Query a CS2 server and return info
 * @param string $ip Server IP
 * @param int $port Server port
 * @return array Server info with defaults on failure
 */
function queryServer($ip, $port) {
    $query = new SourceQuery($ip, $port, 2);
    $info = $query->getInfo();

    if ($info === false) {
        return [
            'online' => false,
            'hostname' => 'Offline',
            'map' => 'unknown',
            'players' => 0,
            'maxplayers' => 0,
            'bots' => 0,
        ];
    }

    $info['online'] = true;
    return $info;
}
