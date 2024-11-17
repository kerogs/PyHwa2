<?php

require_once('../config.php');

if (!isset($_GET['nid'])) {
    header('Location: err?e=no profile was indicated.');
    exit();
}


$profileID = htmlspecialchars($_GET['nid']);

$storageFile = '../backend/storage.json';

$storageJSON = json_decode(file_get_contents($storageFile), true);

$nameidJSON = $storageJSON['nameid'];

$profileIDSearch = array_search($profileID, array_column($nameidJSON, 'nameid'));

if ($profileIDSearch === false) {
    header("Location: /err?e=the nameid doesn't match. Don't forget that the nameid MAY differ slightly from the person's nickname. Or the user simply doesn't exist.");
    exit();
}

$profileUuid = $nameidJSON[$profileIDSearch]['uuid'];

// get profile content
$profileJSON = json_decode(file_get_contents("../backend/storage/accounts/" . $profileUuid . '.json'), true);

// Debug
// var_dump($profileJSON);

?>

<!DOCTYPE html>
<html lang="<?= $kpf_config["seo"]["lang_short"] ?>">

<head>
    <base href="/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once '../inc/head.php' ?>
    <title><?= $kpf_config["seo"]["title_short"] ?></title>
    <link rel="stylesheet" href="src/css/style.css">

    <!-- src -->
    <link rel="stylesheet" href="./node_modules/boxicons/css/boxicons.min.css">
</head>

<?php if ($profileJSON['attributes']['background'] !== false) : ?>

<?php endif; ?>

<?php if ($profileJSON['attributes']['banner'] !== false) : ?>

<?php else : ?>
    <style>
        .profile_header__container {
            background-image: url('./src/img/default/default_banner.svg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
<?php endif; ?>

<body>
    <?php require_once '../inc/header.php' ?>

    <div class="profile_header">

        <div class="profile_header__container">

            <div class="bottom">
                <div class="left">
                    <div class="pfp">
                        <img class="<?= $jsonAccount['attributes']['decorations']['border'] ?>" src="<?= $profileJSON['attributes']['pfp_encode'] . $profileJSON['attributes']['pfp'] ?>" alt="">
                    </div>
                </div>
                <div class="right">
                    <p class="username"><span style="<?= $profileJSON['attributes']['decorations']['name_style'] ?>" class="<?= $profileJSON['attributes']['decorations']['name_class'] ?>"><?= $profileJSON['attributes']['decorations']['svg_icon'] ?> <?= $profileJSON['attributes']['username'] ?></span> <span class="nameid"><?= $profileJSON['attributes']['decorations']['title'] ?></span></p>
                    <?php if ($profileJSON['permission']['owner'] || $profileJSON['permissions']['admin'] || $profileJSON['permissions']['upload']): ?>
                    <div class="badgearea">
                        <?php if ($profileJSON['permissions']['owner']) : ?>
                            <span>Website owner</span>
                        <?php endif; ?>
                        <?php if ($profileJSON['permissions']['admin']) : ?>
                            <span>Administrator</span>
                        <?php endif; ?>
                        <?php if ($profileJSON['permissions']['upload']) : ?>
                            <span>Publisher</span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <p class="dateJoin">
                        Join : <?= $profileJSON['attributes']['creation_date'] ?>
                    </p>
                </div>
            </div>

        </div>

    </div>

    <div class="profile_split">
        <div class="profile_split__container">
            <div class="left">
                <div class="titlee">
                    <p>Informations</p>
                </div>
                <?php if ($profileJSON['attributes']['decorations']['svg_icon'] === "") :  ?>
                    <p>This user has no icon</p>
                <?php else: ?>
                    <p class="icon"><span>Icon : </span> <span><?= $profileJSON['attributes']['decorations']['svg_icon'] ?></span></p>
                <?php endif; ?>
                <p class="name"> Name: <span style="<?= $profileJSON['attributes']['decorations']['name_style'] ?>" class="<?= $profileJSON['attributes']['decorations']['name_class'] ?>"> <?= $profileJSON['attributes']['username'] ?></span></p>
                <p>NameID: <?= $profileJSON['nameid'] ?></p>
                <p>Title: <?= $profileJSON['attributes']['decorations']['title'] ?></p>
                <br>
                <div class="titlee">
                    <p>Stats</p>
                </div>
                <h3>Read</h3>
                <p><?= $profileJSON['attributes']['stats']['manga'] ?> Manga </p>
                <p><?= $profileJSON['attributes']['stats']['manhwa'] ?> Manhwa</p>
                <p><?= $profileJSON['attributes']['stats']['manhua'] ?> Manhua </p>
                <br>
                <h3>Favorites</h3>
                <?php if ($profileJSON['attributes']['stats']['favorites'] != 0) : ?>
                    <p>Currently <?= $profileJSON['attributes']['stats']['favorites'] ?> content in favorites</p>
                <?php else : ?>
                    <p>No content in favorites</p>
                <?php endif; ?>
                <br>
                <h3>Messages</h3>
                <?php if (!$profileJSON['attributes']['stats']['messages']) : ?>
                    <p>This user has sent no message</p>
                <?php else: ?>
                    <p><?= $profileJSON['attributes']['stats']['messages'] ?> Messages</p>
                    <p>Last message in <a href="$profileJSON['attributes']['stats']['message_attributes']['read_url']"><?= $profileJSON['attributes']['stats']['message_attributes']['read_name'] ?></a></p>
                <?php endif; ?>
            </div>
            <div class="right">
                <p class="bio">
                    <?= htmlspecialchars($profileJSON['attributes']['bio']) ?>
                </p>
            </div>
        </div>
    </div>

    <?php require_once '../inc/script.php' ?>
</body>

</html>