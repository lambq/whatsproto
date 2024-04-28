<?php

namespace Lambq\WhatsProto;

class Connection
{
    public function connect()
    {
        if ($this->is_connected()) {
            \Lambq\WhatsProto\Log('Connection: You\'re already connected...', 'warning');
        }
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket !== false) {
            $result = socket_connect($socket, 'e' . rand(1, 16) . '.whatsapp.net', \Lambq\WhatsProto\Constants::WHATSAPP_PORT);
            if ($result === false) {
                $socket = false;
            }
        }
        if ($socket !== false) {
            socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => \Lambq\WhatsProto\Constants::TIMEOUT_SEC, 'usec' => \Lambq\WhatsProto\Constants::TIMEOUT_USEC]);
            socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, ['sec' => \Lambq\WhatsProto\Constants::TIMEOUT_SEC, 'usec' => \Lambq\WhatsProto\Constants::TIMEOUT_USEC]);
            $this->socket = $socket;
            \Lambq\WhatsProto\Log('Connection: Connected to the server...', 'connection');
            return true;
        } else {
            \Lambq\WhatsProto\Log('Connection: Unable to connect to the server!', 'error');
            throw new \Lambq\WhatsProto\ConnectionException('Unable to connect to the server!');
            return false;
        }
    }
    public function is_connected()
    {
        return $this->socket !== null;
    }
    public function disconnect()
    {
        if (is_resource($this->socket)) {
            @socket_shutdown($this->socket, 2);
            @socket_close($this->socket);
        }
        $this->socket = null;
        $this->loginStatus = \Lambq\WhatsProto\Constants::DISCONNECTED_STATUS;
        \Lambq\WhatsProto\Log('Connection: Disconnected from the server...', 'connection');
    }
}
