<?php

declare(strict_types=1);

/**
 * @param string $string
 * @return string
 */
function e(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES);
}

$config = require_once(__DIR__ . '/config.php');

if (isset($_POST['submitted'])) {
    $result = json_decode(file_get_contents(
        'https://www.google.com/recaptcha/api/siteverify'
            . "?secret={$config['secretkey']}&response={$_POST['recaptcha_response']}"
    ));
}
?>
<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>reCAPTCHA v3 フォーム</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col"></div>
            <div class="col-lg-6">
                <h1 class="mt-3">reCAPTCHA v3 フォーム</h1>
                <form action="" method="post">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" name="submitted" value="1">
                            <input type="hidden" name="recaptcha_response" id="recaptcha_response">
                            <button type="submit" class="btn btn-primary">送信</button>
                            <a href="" class="btn btn-secondary">リセット</a>
                        </div>
                    </div>
                    <?php if (isset($_POST['submitted'])) : ?>
                        <div class="card mt-3">
                            <div class="card-body">
                                <table class="table table-striped m-0">
                                    <tbody>
                                        <tr>
                                            <th>結果</th>
                                            <td><?= $result->success ? '成功' : '失敗' ?></td>
                                        </tr>
                                        <?php if ($result->success) : ?>
                                            <tr>
                                                <th>試行日時</th>
                                                <td><?= e(date('Y-m-d H:i:s', strtotime($result->challenge_ts))) ?></td>
                                            </tr>
                                            <tr>
                                                <th>スコア</th>
                                                <td><?= e((string)$result->score) ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
            <div class="col"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=<?= e($config['sitekey']) ?>"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('<?= e($config['sitekey']) ?>', {
                action: 'sent',
            }).then(function(token) {
                document.getElementById('recaptcha_response').value = token;
            });
        });
    </script>
</body>

</html>