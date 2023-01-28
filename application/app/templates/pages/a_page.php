<?php
/**
 * A template with boilerplate html code.
 *
 * @see \app\site\control\DefaultPageControl::getDefaultPageTemplate()
 */

namespace app\templates\pages;

/** @var mixed $this */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ?php $this->renderMetaTags(); ?> TODO DefaultPageC addMetadata -->
    <title><?php $this->renderTitle(); ?></title>
    <link rel="icon" type="image/x-icon" href="/ico/favicon-16x16.png">
    <link rel="icon" type="image/x-icon" href="/ico/favicon-32x32.png">
    <!-- <link rel="icon" href="./favicon.ico" type="image/x-icon"> TODO DefaultPageC setFavicon -->
    <link rel="stylesheet" href="/css/normalize.css">
    <!-- ?php $this->renderStylesheetsLinks(); ? -->
    <?php $this->renderScriptLinks(); ?>
</head>
<body>
the body
<?php $this->renderContent(); ?>
</body>
</html>