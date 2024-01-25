<?php

namespace Control;

function SendResponse(mixed $content, int $requestCode): void
{
    http_response_code($requestCode);
    echo $content;
    die;
}
