<?php
// print_r($_FILES['attachment']);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $file = ($_FILES['attachment']);
    $srcfileName = $file['name'];
    $newFilePath = __DIR__ . '/uploads/' . $srcfileName;
    $tmpFilePath = $file['tmp_name'];

    $extension = pathinfo($srcfileName, PATHINFO_EXTENSION);
    $allowedExtension = ['jpg', 'jpeg', 'png', 'svg', 'gif', 'ico'];


    if (!in_array($extension, $allowedExtension) && $_FILES['attachment']['size'] > 2000000) {
        $error = "Загрузка файла с таким типом и размером запрещена ";
    } elseif ($file['error'] !== 0) {
        $error = "Ошибка при загрузке файла";
    } elseif (file_exists($newFilePath)) {
        $error = "Файл с таким именем существует";
    } elseif (move_uploaded_file($tmpFilePath, $newFilePath)) {
        $result = 'http://localhost/uploads_files/uploads/' . $srcfileName;
    } else {
        $error = 'Ошибка при загрузке файла';
    }
}


$res = null;
$note = $_POST['text'] ?? "";

if (!empty($note)) {
    $dateTime = date(DATE_ATOM);
    $isWrote = file_put_contents(
        __DIR__ . '/private/feedback.txt',
        $dateTime . PHP_EOL . $note . PHP_EOL,
        FILE_APPEND
    );
    $notes = [];
    foreach ($notes as $note)
        if ($isWrote === false) {
            $res = "Не удалось отправить сообщение";
        } else {
            $res = "Сообщение успешно отправлено";
        }
}
?>

<!-- https://disk.yandex.ru/d/JRYfCwwyJfoJpA -->

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Загрузка файлов на сервер</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <?php
        if (!empty($error)) {
            echo $error;
        } elseif (!empty($result)) {
            echo $result;
        }
        ?>
        <form action="./index.php" method="post" enctype="multipart/form-data">
            <input type="file" name="attachment">
            <button type="submit">Загрузить файл</button>
        </form>
        <br>
        <?php
        $images = glob('uploads/' . "*.{jpeg,jpg,png,svg,gif,ico}", GLOB_BRACE);
        if (count($images) > 0) {
            foreach ($images as $image) {
                echo '<img src="' . $image . '" class = "img" />';
            }
        }
        ?>
        <form action="./index.php" method="post" class="feedback-form" class="note">
            <textarea name="text" id="text" cols="50" rows="5" placeholder="Введите заметку"></textarea>
            <br>
            <textarea name="note" id="note" cols="39" placeholder=""></textarea>
            <button class="btn-submit" type="submit">Отправить</button>
        </form>
    </div>

</body>

</html>