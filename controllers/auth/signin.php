<?php
session_start();
require_once __DIR__ . '/../../repositories/userRepository.php';
require_once __DIR__ . '/../../validations/app/validate-sign-in.php';

if (isset($_POST['user'])) {
    if ($_POST['user'] == 'login') {
        login($_POST);
    }

    if ($_POST['user'] == 'logout') {
        logout();
    }
}

function login($req)
{
    $data = isLoginValid($req);
    $valido = checkErrors($data, $req);

    if ($valido) {
        $data = isPasswordValid($data);
    }

    $user = checkErrors($data, $req);

    if ($user && !$user['deleted_at']) { // Adiciona verificação para usuário não deletado
        doLogin($data);
    } elseif ($user['deleted_at']) {
        $_SESSION['errors'] = "Usuário deletado. Não é possível fazer login.";
        $params = '?' . http_build_query($req);
        header('location: /php-project/pages/public/signin.php' . $params);
    }
}

function checkErrors($data, $req)
{
    if (isset($data['invalid'])) {
        $_SESSION['errors'] = $data['invalid'];
        $params = '?' . http_build_query($req);
        header('location: /php-project/pages/public/signin.php' . $params);
        return false;
    }

    unset($_SESSION['errors']);
    return true;
}

function doLogin($data)
{
    $_SESSION['id'] = $data['id'];
    $_SESSION['name'] = $data['name'];

    setcookie("id", $data['id'], time() + (60 * 60 * 24 * 30), "/");
    setcookie("name", $data['name'], time() + (60 * 60 * 24 * 30), "/");

    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/php-project/pages/public/dashboard.php';
    header('Location: ' . $home_url);
}

function logout()
{
    if (isset($_SESSION['id'])) {

        $_SESSION = array();

        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600);
        }
        session_destroy();
    }

    setcookie('id', '', time() - 3600, "/");
    setcookie('name', '', time() - 3600, "/");

    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/php-project';
    header('Location: ' . $home_url);
}
