<form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="post"
    onsubmit="document.getElementById('submit-button').disabled = true;">
    <input type="hidden" name="token" value="<?= md5(uniqid()) ?>">
    <input type="submit" value="Save Data" id="submit-button">
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = __DIR__ . '/form.db';
    $db = new PDO("sqlite:/$db");
    $db->beginTransaction();
    $sth = $db->prepare('select * from form where token = ?');
    $sth->execute([$_POST['token']]);
    if (count($sth->fetchAll())) {
        echo "Already submitted";
        $db->rollback();
    } else {
        $sth = $db->prepare('insert into forms (token) values (?)');
        $sth->execute([$_POST['token']]);
        $db->submit();
        echo "Success";
    }
}