<?php

namespace Lambq\WhatsProto;

/**
 *  Logger for WhatsProto
 *
 *  @author Lambq
 *  @copyright 2017
 *  @license GNU AGPL v3
 *
 *  @method boolean Log(string $string, string $default_style, string $foreground_color, string $background_color, string $style) This method will print the log in a console using the style and colors passed by parameters
 */
    class Logger
    {
        /**
         * Array of all foreground colors
         * @var array
         */
        const FOREGROUND_COLORS = [
      'black' => '0;30',
      'dark_gray' => '1;30',
      'blue' => '0;34',
      'light_blue' => '1;34',
      'green' => '0;32',
      'light_green' => '1;32',
      'cyan' => '0;36',
      'light_cyan' => '1;36',
      'red' => '0;31',
      'light_red' => '1;31',
      'purple' => '0;35',
      'light_purple' => '1;35',
      'brown' => '0;33',
      'yellow' => '1;33',
      'light_gray' => '0;37',
      'white' => '1;37'
    ];
        /**
         * Array of all background colors
         * @var array
         */
        const BACKGROUND_COLORS = [
      'black' => '40',
      'red' => '41',
      'green' => '42',
      'yellow' => '43',
      'blue' => '44',
      'magenta' => '45',
      'cyan' => '46',
      'light_gray' => '47'
    ];
        /**
         * Array of all styles
         * @var array
        */
        const STYLE = [
      'bold' => '1',
      'dim' => '2',
      'italic' => '3',
      'underlined' => '4',
      'blink' => '5',
      'inverted' => '7',
      'hidden' => '8'
    ];
        /**
         * Array of default logs style
         * @var array
         */
        const DEFAULT_STYLE = [
      'normal' => ['foreground' => 'white', 'background' => 'blue', 'style' => 'italic'],
      'warning' => ['foreground' => 'white', 'background' => 'cyan', 'style' => 'bold'],
      'error' => ['foreground' => 'white', 'background' => 'red', 'style' => 'bold'],
      'connection' => ['foreground' => 'white', 'background' => 'green', 'style' => 'italic']
    ];
        public static function Log($string, $default_style = null, $foreground_color = null, $background_color = null, $style = null)
        {
            /**
             *  This method will print the log in a console using the style and colors passed by parameters
             *
             * @internal
             * @param string $string The string you want to log
             * @param string $default_style Default style
             * @param string $foreground_color The foreground color
             * @param string $background_color The background color
             * @param string $style The style
             * @return boolean
             */
            /**
             * The variable that will contain the formatted string
             * @var string
             */
            $formatted_string = '';
            /**
             * This variable will be used in a if structure to determinate if the formatted string will end with "'\033[0m'"
             * @var boolean
             */
            if (isset($default_style) and is_string($default_style) and !empty($default_style)) {
                if (isset(self::DEFAULT_STYLE[$default_style])) {
                    echo "\033[" . self::FOREGROUND_COLORS[self::DEFAULT_STYLE[$default_style]['foreground']] . 'm' . "\033[" . self::BACKGROUND_COLORS[self::DEFAULT_STYLE[$default_style]['background']] . 'm' . "\033[" . self::STYLE[self::DEFAULT_STYLE[$default_style]['style']] . 'm' . $string . "\033[0m" . PHP_EOL;
                    return true;
                }
            }
            $one_parameter_is_valid = false;
            // Checking if $foreground_color is valid, then if true the system will format the string using $foreground_color
            if (isset(self::FOREGROUND_COLORS[$foreground_color])) {
                $one_parameter_is_valid = true;
                $formatted_string .= "\033[" . self::FOREGROUND_COLORS[$foreground_color] . 'm';
            }
            // Checking if $background_color is valid, then if true the system will format the string using $background_color
            if (isset(self::BACKGROUND_COLORS[$background_color])) {
                $one_parameter_is_valid = true;
                $formatted_string .= "\033[" . self::BACKGROUND_COLORS[$background_color] . 'm';
            }
            // Checking if $style is valid, then if true the system will format the string using $style
            if (isset(self::STYLE[$style])) {
                $one_parameter_is_valid = true;
                $formatted_string .= "\033[" . self::STYLE[$style] . 'm';
            }
            if ($one_parameter_is_valid) {
                $formatted_string .=  $string . "\033[0m";
            } else {
                $formatted_string = $string;
            }
            echo $formatted_string . PHP_EOL;
            return true;
        }
    }
