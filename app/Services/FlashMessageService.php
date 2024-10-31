<?php

namespace MyCloudPress\Services;

class FlashMessageService
{


    
    protected $classes = array('error', 'updated');


    
    protected $messages = array();


    
    public function __construct($options=[])
    {
        foreach ($options as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }

        add_action('admin_notices', array($this, 'show_flash_message'));
    }


    
    public function session_end()
    {
        unset($_SESSION['flash_messages']);
    }


    
    public function set_flash_messages($messages)
    {
        if (isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = array_merge($_SESSION['flash_messages'], $messages);
        } else {
            $_SESSION['flash_messages'] = $messages;
        }
    }


    
    public function get_flash_messages()
    {
        if (isset($_SESSION['flash_messages'])) {
            return $_SESSION['flash_messages'];
        }

        return array();
    }


    
    public function queue_flash_message($name, $message)
    {
        $messages = array();


        
        $classes       = apply_filters('flashmessage_classes', $this->classes);


        
        $default_class = apply_filters('flashmessages_default_class', 'updated');

        $class = $name;
        if (!in_array($name, $classes)) {
            $class = $default_class;
        }

        $messages[$class][] = $message;

        $this->set_flash_messages($messages);

        return $this;
    }


    
    public function show_flash_message()
    {
        $messages = $this->get_flash_messages();
        if (is_array($messages)) {
            foreach ($messages as $class => $messages) {
                $this->display_flash_message_html($messages, $class);
            }
        }

        $this->session_end();
    }


    
    private function display_flash_message_html($messages, $class)
    {
        foreach ($messages as $message) {
            $message_html = "<div id=\"message\" class=\"{$class}\"><p>{$message}</p></div>";

            echo apply_filters('flashmessage_html', $message_html, $message, $class);
        }
    }

}