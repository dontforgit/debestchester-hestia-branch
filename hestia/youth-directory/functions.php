<?php

/**
 * Debug function to dump variable contents and optionally die.
 *
 * @param $vVariable
 *             Variable to dump out contents.
 * @param bool $vDie
 *             Optional: Should execute of the script halt?
 */
function echo_pre($vVariable, $vDie = false)
{
    if (is_object($vVariable) || is_array($vVariable)) {
        echo "<pre>", print_r($vVariable), "</pre>";
    } else {
        var_dump($vVariable);
    }

    if ($vDie) {
        die;
    }
}