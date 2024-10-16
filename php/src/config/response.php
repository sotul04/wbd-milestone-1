<?php

function json_response_success($data) {
    echo(json_encode(array('status' => 'success', 'data' => $data)));
}

function json_response_fail($msg) {
    echo(json_encode(array('status' => 'bad', 'data' => $msg)));
}
