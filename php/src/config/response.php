<?php

function json_response_success($data) {
    echo(json_encode(array('status' => true, 'data' => $data)));
}

function json_response_fail($msg) {
    echo(json_encode(array('status' => false, 'data' => $msg)));
}