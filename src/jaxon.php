<?php

// Array of approved HTML tags
$approvedTags = ['div', 'a', 'span', 'p', 'h1', 'h2', 'h3', 'ul', 'li', 'img'];

// Check if the required POST variables are set
if (isset($_POST['link']) && isset($_POST['config'])) {
    $link = $_POST['link'];
    $config = json_decode($_POST['config'], true);

    // Fetch data from the URL
    $jsonData = file_get_contents($link);
    if ($jsonData === false) {
        echo "Error fetching data from the URL.";
        exit;
    }

    // Check if the data is valid JSON
    $data = json_decode($jsonData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Error: Data is not in JSON format.";
        exit;
    }

    // Function to generate HTML elements according to config
    function generateHtml($data, $config, $approvedTags) {
        global $approvedTags;
        $html = '';

        foreach ($config as $tag => $attributes) {
            if (!in_array($tag, $approvedTags)) {
                continue;
            }

            $html .= "<$tag";

            foreach ($attributes as $attr => $value) {
                if (is_array($value)) {
                    // Nested tag handling
                    $html .= ">" . generateHtml($data, array($attr => $value), $approvedTags);
                } else {
                    // Parse value (supports nested values)
                    $parsedValue = parseValue($value, $data);

                    if ($attr == 'text') {
                        $html .= ">" . htmlspecialchars($parsedValue);
                    } else {
                        $html .= " $attr=\"" . htmlspecialchars($parsedValue) . "\"";
                    }
                }
            }

            if (!array_key_exists('text', $attributes) && !is_array($value)) {
                $html .= ">";
            }

            $html .= "</$tag>";
        }

        return $html;
    }

    // Parse nested value
    function parseValue($value, $data) {
        // Check if value is nested
        if (!preg_match("/\w+.+\w/", $value)) {
            return $value;
        }
        
        // Split value by dot
        $split = explode('.', $value);
        
        // The array value
        $dataValue = $data;

        foreach ($split as $key) {
            if (isset($dataValue[$key])) {
                $dataValue = $dataValue[$key];
            } else {
                return $value;
            }
        }

        return $dataValue;
    }



    // Apply HTML generation
    $result = [];
    if (is_array($data)) {
        foreach ($data as $item) {
            $result[] = generateHtml($item, $config, $approvedTags);
        }
    } else {
        $result = generateHtml($data, $config, $approvedTags);
    }

    // Return the HTML
    echo implode("\n", $result);
} else {
    echo "Error: 'link' and 'config' POST variables are required.";
}

?>
