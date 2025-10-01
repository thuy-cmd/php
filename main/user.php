<?php
    $user = [
        "name" => "Hoàng Văn Bit",
        "nickname"=> "Bit Eric",
        "age"=> "20",
        "email"=> "hoangbit185@gmail.com",
        "class"=> "K9TIN",
    ];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user["name"] = $_POST["name"];
        $user["nickname"] = $_POST["nickname"];
        $user["email"] = $_POST["email"];
        $user["class"] = $_POST["class"];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello nhom 3</title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        html {
            font-size: 62.5%;
        }
        body {
            position: relative;
            text-align: center;
            font-size: 1.6rem;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }
        .info__name {
            margin-top: 10px;
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: center;
        }
        .class {
            margin-top: 10px;
        }
        .button {
            border: none;
            outline: none;
            cursor: pointer;
            padding: 8px 16px;
            border-radius: 12px;
        }
        .modal {
            position: relative;
            display: none;
            align-items: center;
            justify-content: center;
        }
        .modal.show {
            display: flex;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.4);
        }
        .overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .edit__modal {
            min-width: 200px;
            align-items: center;
            position: absolute;      
        }
        .edit__content {
            padding: 20px;
            border-radius: 20px;
            background-color: aliceblue;
        }
    </style>
</head>
<body>
    <div class="info">
        <h1>Hello, welcome to my profile</h1>
        <div class="container">
            <div class="info__name">
                <h2>Name: <?= $user["name"]?></h2>
                <h3>Nickname: <?= $user["nickname"]?></h3>
            </div>
            <div class="email">
                <p>Email: <?= $user["email"] ?></p>
            </div>
            <div class="class"><h2><?= $user["class"] ?></h2></div>
            <button class="button">Edit info</button>
        </div>
    </div>
    <div class="link">
        <a href=""></a>
    </div>
    <div class="modal">
        <div class="overlay"></div>
        <div class="edit__modal">
            <div class="edit__content">
                <h2>Edit information</h2>
                <form action="" method="POST">
                    <div class="form__group">
                        <label for="editname">Enter your name:</label>
                        <input id="editname" type="text" name="name" class="form__input" value="<?= $user['name'] ?>">
                    </div>
                    <div class="form__group">
                        <label for="nickname">Enter your nickname:</label>
                        <input id="nickname" type="text" name="nickname" class="form__input" value="<?= $user['nickname'] ?>">
                    </div>
                    <div class="form__group">
                        <label for="editemail">Enter your email:</label>
                        <input id="editemail" type="email" name="email" class="form__input" value="<?= $user['email'] ?>">
                    </div>
                    <div class="form__group">
                        <label for="editclass">Enter your class:</label>
                        <input id="editclass" type="text" name="class" class="form__input" value="<?= $user['class'] ?>">
                    </div>
                    <button class="save" type="submit">Save</button>
            </form>
            </div>
        </div>
    </div>
    <script>
        const modal = document.querySelector(".modal");
        const button = document.querySelector(".button");
        const overlay = document.querySelector(".overlay");
        const save = document.querySelector('.save');

        button.addEventListener('click', () => {
            modal.classList.add('show')
        })
        overlay.addEventListener('click', () => {
            modal.classList.remove('show')
        })
    </script>
</body>
</html>