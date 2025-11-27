<script src="./lib/js/sweetalert2.all.js"></script>
<?php
ob_start();
if (isset($message)) {
    foreach ($message as $message) {
        if (isset($message['icon']) && isset($message['type']) && isset($message['message'])) {
            echo "<div></div><script>
                Swal.fire({
                    icon: '" . $message['icon'] . "',
                    title: '" . $message['type'] . "',
                    text: '" . $message['message'] . "'
                }).then((result) => {
                    // This code will execute when the user clicks the 'Okay' button
                    " . (isset($message['redirection']) ? "if (result.isConfirmed) {
                        // Redirect to another page
                        window.location.href = '{$message['redirection']}';
                    }" : "") . "
                });
            </script>";
        }
    }
}
?>
<div></div>