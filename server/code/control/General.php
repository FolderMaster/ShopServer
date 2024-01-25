<?php

namespace Control;

function CheckVariable(mixed &$variable, mixed $default): void
{
    if ($variable === null) {
        $variable = $default;
    }
}
