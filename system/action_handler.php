<?php

function handleActions($performActions)
{
    $action = isset($_POST['action']) ? $_POST['action'] : null;

    if ($action == null) {
        // do nothing;
        return;
    }

    $performActions($action);
}
