<?php
session_start();//Старт сесси
if($_SERVER['REQUEST_METHOD'] == 'GET'){//GET запрос для проверки логина и пароля
    $_SESSION['message'] = FALSE;
    $mess = empty($_COOKIE['message']) ? '0' : $_COOKIE['message'];
    setcookie('message','1',1);
}else if($_SERVER['REQUEST_METHOD'] == 'POST'){//POST запрос для отправки логина и пароля

    require 'connect/connection.php';//Подключение файла connection.php, в котором создано sqli соеденение с бд

    $login = $_POST['login'];
    $password =$_POST['password'];

    $check_user = mysqli_query($connect, "SELECT * FROM USERS WHERE login = '$login'");//Берем все поля из таблицы у которых логин совпадает с введеныи
    if(mysqli_num_rows($check_user) > 0){//Проверка на то существует ли такой логин
        $user = mysqli_fetch_assoc($check_user);//Берем именнованые поля у совпадающего логина
        if(password_verify($password,$user['pass'])){// Проверка на совпадение пароля из таблицы с введеным
            $id = $user['id'];//берем айди логина
            $check_power = mysqli_query($connect, "SELECT * FROM super_power WHERE human_id = $id");//Проверяем наличие способностей
            $power =mysqli_fetch_assoc($check_power);//берем эти способности
            $_SESSION['user'] = [
                //Создаем именнованую сессию user и вносим в нее данные из таблицы
                "id" => $user['id'],
                "name" => $user['name'],
                "email" => $user['mail'],
                "bio" => $user['bio'],
                "year" => $user['date'],
                "gender" => $user['gender'],
                "limbs" => $user['limbs'],
                "ability" =>$power['superabilities']
            ];

            //Создаем куки для дальнейшего заполнения полей, данными прежде введенными пользователем
            setcookie('name_value',$_SESSION['user']['name'],time() + 12 * 30 * 24 * 60 * 60);
            setcookie('email_value',$_SESSION['user']['email'], time() + 12 * 30 * 24 * 60 * 60);
            setcookie('bio_value',$_SESSION['user']['bio'], time() + 12 * 30 * 24 * 60 * 60);
            setcookie('year_value',$_SESSION['user']['year'], time() + 12 * 30 * 24 * 60 * 60);
            setcookie('gender_value',$_SESSION['user']['gender'], time() + 12 * 30 * 24 * 60 * 60);
            setcookie('limbs_value',$_SESSION['user']['limbs'],time() + 12 * 30 * 24 * 60 * 60) ;
            setcookie('ability_value',$_SESSION['user']['ability'], time() + 12 * 30 * 24 * 60 * 60);
            setcookie('agree_value', '1', time() + 12 * 30 * 24 * 60 * 60) ;
            setcookie('message','1',1);

            header('Location: index.php');
        }else{//Если пароль не совпадает выбрасывает сообщение об ошибке
            $_SESSION['message'] = TRUE;
            header('Location: login.php');
            setcookie('message','1');
        }
    }else{//Если такой логин не найден выбрасывает сообщение об ошибке
        $_SESSION['message'] = TRUE;
        setcookie('message','1');
        header('Location: login.php');
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Вход</title>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>


<form  method="post" action="login.php">
    <div class="alert alert-danger"role="alert" <?php
    if($mess == '0'){
        print('hidden');
    }else{
        print(' ');
    }
    ?>>
        <?php
        if($mess == '1'){
            print('Неправильный логин или пароль');
        }
        ?>
    </div>
    <div class="popup" id="popup">
        <div class="popup__body">
            <div class="popup__content">
                <div class="popup__title">Вход</div>
                <div class="popup__text">
                    <div>
                        <input type="text" name="login" class="login__elem" placeholder="Логин">
                    </div>
                    <div>
                        <input type="text" name="password" class="login__elem" placeholder="Пароль">
                    </div>
                    <div>
                        <input type="submit" class="popup__btn" value="Войти" name="do_login">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
</html>