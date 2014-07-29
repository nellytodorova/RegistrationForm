<!DOCTYPE html>
<html>
<head>
    <title>Регистрационна форма</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?= $http_root_css; ?>styles.css" />
    <script type="text/javascript" src="<?= $http_root_js; ?>jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="<?= $http_root_js; ?>jquery.form.min.js"></script>
    <script type="text/javascript" src="<?= $http_root_js; ?>actions.js"></script>
</head>
<body>
    <div><strong>Регистрационна форма</strong></div><br />
    <div id="result" <?= !empty($resultClass) ? 'class="' . $resultClass . '"' : '' ?>>
         <?= !empty($resultMessage) ? $resultMessage : '' ?>
    </div>
    <form action="index.php" method="post" id="regForm" name="regForm">
        <?php
        if (is_array($fieldsConfig) && !empty($fieldsConfig)) {
            foreach ($fieldsConfig as $field => $config) {
                echo '<div>';
                    if (!empty($config['label'])) {
                        echo '<div>';
                        echo $config['label'];
                        echo ((int)$config['notEmpty'] == 1) ? ' <span>*</span>' : '';
                        echo '</div>';
                    }

                    if (isset($verify[$field])) {
                        echo '<div class="error">' . $verify[$field] . '</div>';
                    }

                    switch($config['type']) {
                        case 'text':
                        case 'password':
                            echo '<input id="' . $field . '" name="' . $field . '" type="' . $config['type'] . '" size="' . $config['size'] . '" maxlength="' . $config['maxSymbols'] . '" /></div>';
                            break;
                    }
                echo '</div>';
            }
        }
        ?>
        <div><input type="hidden" name="submiForm" value="1" /></div>
        <div><input type="submit" name="submit" value="Регистрация" /></div>
    </form>
</body>
</html>