<?php

function jsonResponse($template, $data)
{
    $response = new stdClass();
    $response->template = $template;
    $response->data = $data;

    $json = json_encode($response, JSON_NUMERIC_CHECK);
    header('Etag: "'.sha1($json).'"');
    header('Content-type: application/json');
    echo $json;
}
