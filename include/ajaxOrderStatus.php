<?php
if ($_POST) {
    include $_SERVER['DOCUMENT_ROOT'] . '/include/functions.php';

    if ($_POST['orderId']) {

        $id = (int) htmlspecialchars($_POST['orderId']);

        if (isset($_POST['status'])) {
            $status = htmlspecialchars($_POST['status']);
            if ($status == 'true') {
                $status = 1;
            } else {
                $status = 0;
            }
            $data = updateStatusOrder($id, $status);
        }
    }
    if (isset($data)) {
        echo json_encode($data);
    } else {
        echo json_encode(false);
    }
}

