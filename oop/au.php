<?php
    interface auth {
        public function login($credentials);
    }

    class EmailAuth implements auth {
        public function login($credentials) {
            echo "Xac thuc bang email: {$credentials['email']}";
        }
    }

    $email = new EmailAuth();
    echo $email->login(['email' => 'example@example.com']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authenactor</title>
</head>

<body>

</body>

</html>
