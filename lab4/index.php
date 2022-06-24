<?php

$ip = 'http://185.225.34.31:228/';
$users = array(
    'admin' => 'admin',
    'user' => 'user',
);

if (!isset($_COOKIE['username']) || ($_COOKIE['username']==''))
{
?>
    <form  name = "authentication" method = "GET" action = "/">
    <input name="username" placeholder="логин"/>
    <input name="password" placeholder="пароль"/>
    <input name = "btn" type = "submit" value = "Войти"/>
    <br> </br>
<?php
    PrintMessages();
}
else
{
    $username = $_GET['username'];
    PrintMessages();
?>
    <form  name = "chat" method = "GET" action = "/send">
        <input name="message" placeholder="напишите что-нибудь..."/>
        <input name = "send" type = "submit" value = "отправить"/>
    </form>
    <form action="/logout" method="GET">
    <input type="submit" value="Выйти">
    </form>

<?php
    if($_COOKIE['username']!='')
      echo 'Авторизован как ' . $_COOKIE['username'];
}
if ($_SERVER['REQUEST_URI'] == '/logout?'){
    setcookie('username', '');
    header("Location: $ip");
}

if(isset($_GET['message']) && ($_GET['message']!='') && $_COOKIE['username']!='' && $_GET['send']){
    $message = $_GET['message'];
    AddMessage($_COOKIE['username'], $message);
    header("Location: $ip");
}
if (isset($_GET["btn"] )&& isset($_GET['username'])&& isset($_GET['password'])) {
    if (array_key_exists($_GET['username'], $users) && in_array($_GET['password'],$users)){
        $username = $_GET['username'];
        $password = $_GET['password'];
        setcookie('username', $username, time() + 120);
        header("Location: $ip");
    } else {
        echo "Ошибка авторизации";
    }
}
function AddMessage($username, $message){
    if ($message != ''){
    $db = json_decode(file_get_contents("messages.json"));
    $info = (object) ['date'=>time()+ 60*60*10, 'username' => $username, 'message' => $message];
    $db->messages[] = $info;
    file_put_contents("messages.json", json_encode($db));
    }
    else
        echo 'Ваше сообщение пустое';
}
function PrintMessages(){
    $db = json_decode(file_get_contents("messages.json"));
    foreach($db->messages as $it){
        echo $it->username . ' [' . date('m/d/Y H:i:s', $it->date) . '] : ' . $it->message;
        ?>
        <br></br>
        <?php
        }
}