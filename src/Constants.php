<?php

namespace Lambq\WhatsProto;

class Constants
{
    const WHATSAPP_EXIST_HOST = 'v.whatsapp.net/v2/exist';
    const WHATSAPP_REGISTER_HOST = 'v.whatsapp.net/v2/register';
    const WHATSAPP_CODE_HOST = 'v.whatsapp.net/v2/code';
    const WHATSAPP_SERVERS = ['s.whatsapp.net'];
    const WHATSAPP_ANDROID_SERVERS = ['e.whatsapp.net', 'e1.whatsapp.net', 'e2.whatsapp.net', 'e3.whatsapp.net', 'e4.whatsapp.net', 'e5.whatsapp.net', 'e6.whatsapp.net', 'e7.whatsapp.net', 'e8.whatsapp.net', 'e9.whatsapp.net', 'e10.whatsapp.net', 'e11.whatsapp.net', 'e12.whatsapp.net', 'e13.whatsapp.net', 'e14.whatsapp.net', 'e15.whatsapp.net', 'e16.whatsapp.net'];
    const WHATSAPP_IOS_SERVERS = ['c.whatsapp.net', 'c1.whatsapp.net', 'c2.whatsapp.net', 'c3.whatsapp.net'];
    const WHATSAPP_POST_SERVERS = ['ios' => 'https://sro.whatsapp.net/client/iphone/iq.php', 'android' => 'https://sro.whatsapp.net/client/android/iq.php'];
    const WHATSAPP_PORT = 443;
    const WHATSAPP_VERSION = '2.17.420';
    const WHATSAPP_DEVICE = 'armani';
    const WHATSAPP_DEVICE_OS = 'Android';
    const WHATSAPP_DEVICE_OS_VERSION = '7.0';
    const WHATSAPP_DEVICE_MANUFACTER = 'Xiaomi';
    const WHATSAPP_USER_AGENT = 'WhatsApp/2.17.348 Android/7.0 Device/Xiaomi-HM_1SW';
    const WHATSAPP_VERSION_CHECKER = 'https://coderus.openrepos.net/whitesoft/whatsapp_scratch';
    const DISCONNECTED_STATUS = 'disconnected';
    const CONNECTED_STATUS = 'connected';
    const TIMEOUT_SEC = 2;
    const TIMEOUT_USEC = 0;
}
