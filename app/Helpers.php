<?php

if(!function_exists('delete_mcp_api_key')) {
    
    function delete_mcp_api_key()
    {
        delete_option('mcp_api_key');
    }
}

if(!function_exists('add_mcp_api_key')) {
    
    function add_mcp_api_key($api_key)
    {
        update_option('mcp_api_key', $api_key);
    }
}

if(!function_exists('mcp_api_key')) {
    
    function mcp_api_key()
    {
        return get_option('mcp_api_key');
    }
}

if(!function_exists('mcp_check_api_key')) {
    
    function mcp_check_api_key()
    {
        $apiKey = get_option('mcp_api_key');
        $service = new MyCloudPress\Services\StatsService($apiKey);
        return $service->ping();
    }
}

if(!function_exists('mcp_check_api_key_exists')) {
    
    function mcp_check_api_key_exists($apiKey)
    {
        $service = new MyCloudPress\Services\StatsService($apiKey);
        if($service->ping()) {
            add_mcp_api_key($apiKey);
            return true;
        } else {
            return false;
        }
    }
}

if(!function_exists('mcp_flash')) {
    function mcp_flash($options=[])
    {
        return new MyCloudPress\Services\FlashMessageService($options);
    }
}

if(!function_exists('mcp_url')) {
    function mcp_url(){
    return sprintf(
        "%s://%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        $_SERVER['REQUEST_URI']
    );
    }
}