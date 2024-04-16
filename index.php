<?php
header("Content-Type: text/html; charset=utf-8");
setlocale(LC_ALL, 'russian');
?>

<style>
    .container {
        display: flex;
        flex-direction: column;
    }
    input {
        width: 300px;
    }
    .button {
        margin-top: 10px
    }
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abiturients</title>
</head>
<body>
    <div class="continer">
        <form method="POST" class="container">
            <label for="id">ФИО:</label>
            <input type="text" name="id">

            <label for="burthYear">Год рождения:</label>
            <input type="text" name="burthYear">

            <label for="gradYear">Год окончания:</label>
            <input type="text" name="gradYear">

            <label for="needRoom">Нужда в общежитии:</label>
            <input type="text" name="needRoom">

            <label for="math">Мат:</label>
            <input type="text" name="math">

            <label for="inf">Инф:</label>
            <input type="text" name="inf">

            <label for="rus">Рус:</label>
            <input type="text" name="rus">

            <label for="phys">Физ:</label>
            <input type="text" name="phys">

            <label for="physEnter">Физ(вступ.):</label>
            <input type="text" name="physEnter">

            <label for="infEnter">Инф(вступ.):</label>
            <input type="text" name="infEnter">

            <input class="button" type=submit name=submit value="send">
            <input class="button" type="submit" name=delete value="delete"> 
            <input class="button" type="submit" name=filter value="filter">
        </form>
    </div>

<?php
    $marksFile = 'marks.txt';
    $personalDataFile = 'personal_data.txt';
    $submit = $_POST['submit'];
    $delete = $_POST['delete'];
    $filter = $_POST['filter'];

    
    if (isset($submit)) {
        $personalData = array(
            $_POST['id'],
            $_POST['burthYear'],
            $_POST['gradYear'],
            $_POST['needRoom'],
        );    
        $marksData = array(
            $_POST['id'],
            $_POST['math'],
            $_POST['inf'],
            $_POST['rus'],
            $_POST['phys'],
            $_POST['physEnter'],
            $_POST['infEnter']
        );

        $file_content = implode("*", $personalData) . PHP_EOL;
        file_put_contents($personalDataFile, $file_content, FILE_APPEND);
        $file_content = implode("*", $marksData) . PHP_EOL;
        file_put_contents($marksFile, $file_content, FILE_APPEND);
    }

    if (isset($filter)) {
        $marksLines = file($marksFile, FILE_IGNORE_NEW_LINES);
        $dataLines = file($personalDataFile, FILE_IGNORE_NEW_LINES);
        $size = count($marksLines);

        for ($i = 0; $i < $size; $i++){
            $marksElements = explode("*", $marksLines[$i]);
            if (((floatval($marksElements[1]) + floatval($marksElements[2]) + floatval($marksElements[3]) + floatval($marksElements[4]) + floatval($marksElements[5]) + floatval($marksElements[6])) / 6) < 4.0){
                echo $personalData[$i] . "<br>";
                echo $marksLines[$i] . "<br>";
            }
        }
    }

    if (isset($delete)) {
        $marksLines = file($marksFile, FILE_IGNORE_NEW_LINES);
        $dataLines = file($personalDataFile, FILE_IGNORE_NEW_LINES);
        file_put_contents($marksFile, "");
        file_put_contents($personalDataFile, "");
        $size = count($marksLines);
                   
        for ($i = 0; $i < $size; $i++){
            $marksElements = explode("*", $marksLines[$i]);
            $dataElements = explode("*", $dataLines[$i]);
            if (((floatval($marksElements[1]) + floatval($marksElements[2]) + floatval($marksElements[3]) + floatval($marksElements[4]) + floatval($marksElements[5]) + floatval($marksElements[6])) / 6) < 4.5){
                if ($dataElements[3] == "да"){
                    continue;
                }
            }
            
            file_put_contents($marksFile, $marksLines[$i] . PHP_EOL, FILE_APPEND);
            file_put_contents($personalDataFile, $dataLines[$i] . PHP_EOL, FILE_APPEND);
        }

        echo "Students:<br>";
        foreach (file($personalDataFile) as $line){
            echo $line . "<br>";                   
        }

        echo "<br>";

        echo "Marks:<br>";
        foreach (file($marksFile) as $line){
            echo $line . "<br>";                   
        }
    }
?>

</body>
</html>