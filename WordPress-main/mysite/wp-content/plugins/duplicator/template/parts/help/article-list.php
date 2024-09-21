<?php

/**
 * @package   Duplicator
 * @copyright (c) 2022, Snap Creek LLC
 */

use Duplicator\Utils\Help\Article;

defined("ABSPATH") or die("");

/**
 * Variables
 *
 * @var \Duplicator\Core\Controllers\ControllersManager $ctrlMng
 * @var \Duplicator\Core\Views\TplMng  $tplMng
 * @var array<string, mixed> $tplData
 */

/** @var Article[] $articles p*/
$articles  = $tplData['articles'];
$listClass = isset($tplData['list_class']) ? 'class="' . $tplData['list_class'] . '"' : '';
?>
<?php if (count($articles) > 0) : ?>
    <ul <?php echo $listClass; ?>>
        <?php foreach ($articles as $article) : ?>
            <li class="duplicator-help-article" data-id="<?php echo (int) $article->getId(); ?>">
                <i aria-hidden="true" class="fa fa-file-alt"></i>
                <a href="<?php echo esc_url($article->getLink()); ?>" target="_blank"><?php echo esc_html($article->getTitle()); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
