<?php
defined('C5_EXECUTE') or die('Access Denied.');

$isEditMode = $isEditMode ?? false;
$areaBlocks = $areaBlocks ?? [];
$app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
/** @var \Concrete\Core\Application\Service\Urls $urls */
$urls = $app->make('helper/concrete/urls');
/** @var \Concrete\Core\Utility\Service\Text $text */
$text = $app->make('helper/text');
/** @var \Concrete\Core\Localization\Service\Date $date */
$date = $app->make('helper/date');

foreach ($areaBlocks as $areaHandle => $area) {
    $blocks = $area['blocks'];
    ?>
    <div class="card mb-2">
        <div class="card-header">
            <?php
            if (isset($area['link'])) {
                echo sprintf('<a href="%s" target="_blank">', $area['link']);
            }
            ?>
            <?= h($areaHandle) ?>
            <?php
            if (isset($area['link'])) {
                echo ' <i class="fas fa-edit"></i></a>';
            }
            ?>
        </div>
        <div class="card-body">
            <?php
            /** @var \Concrete\Core\Block\Block $block */
            foreach ($blocks as $block) {
                /** @var \Concrete\Core\Entity\Block\BlockType\BlockType $blockType */
                $blockType = $block->getBlockTypeObject();
                $cacheLifetime = (int)$block->getBlockCacheSettingsObject()->getBlockOutputCacheLifetime();
                if ($cacheLifetime === 0) {
                    $cacheLifetime = t('Until manually cleared');
                } else {
                    $cacheLifetime = $date->describeInterval($cacheLifetime, true);
                }
                ?>
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <img src="<?= $urls->getBlockTypeIconURL($blockType) ?>" class="media-object"
                             alt="<?= h($blockType->getBlockTypeName()) ?>" width="24" height="24">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="card-title">
                            <?php if ($isEditMode) { ?>
                                <a href="#"
                                   data-link-block="<?= h($block->getBlockID()) ?>"><?= h($blockType->getBlockTypeName()) ?></a>
                            <?php } else { ?>
                                <?= h($blockType->getBlockTypeName()) ?>
                            <?php } ?>
                        </h4>
                        <?php
                        if ($block->getBlockTypeHandle() === 'content') {
                            $content = $block->getController()->getContent();
                            ?>
                            <p class="card-text"><?= h($text->shorten(htmLawed($content))) ?></p>
                            <?php
                        }
                        ?>
                        <div>
                            <span class="badge py-1 px-2 bg-<?php if ($block->cacheBlockOutput()) { ?>primary<?php } else { ?>secondary<?php } ?>"><?= h('Cache') ?></span>
                            <span class="badge py-1 px-2 bg-<?php if ($block->cacheBlockOutputForRegisteredUsers()) { ?>primary<?php } else { ?>secondary<?php } ?>"><?= h('Cache for Registered') ?></span>
                            <span class="badge py-1 px-2 bg-<?php if ($block->cacheBlockOutputOnPost()) { ?>primary<?php } else { ?>secondary<?php } ?>"><?= h('Cache on Post') ?></span>
                            <span class="badge py-1 px-2 bg-<?php if ($block->isAlias()) { ?>primary<?php } else { ?>secondary<?php } ?>"><?= h('Alias') ?></span>
                            <span class="badge py-1 px-2 bg-<?php if ($block->isAliasOfMasterCollection()) { ?>primary<?php } else { ?>secondary<?php } ?>"><?= h('Alias from default') ?></span>
                            <span class="badge py-1 px-2 bg-<?php if ($block->getCustomStyle()) { ?>primary<?php } else { ?>secondary<?php } ?>"><?= h('Has custom style') ?></span>
                        </div>
                        <dl class="row my-3 fs-6 lh-1">
                            <dt class="col-sm-3"><?= t('Block ID') ?></dt>
                            <dd class="col-sm-9"><?= $block->getBlockID() ?></dd>
                            <dt class="col-sm-3"><?= t('Date Added') ?></dt>
                            <dd class="col-sm-9"><?= $date->formatPrettyDateTime($block->getBlockDateAdded()) ?></dd>
                            <?php if ($block->getBlockDateAdded() !== $block->getBlockDateLastModified()) { ?>
                                <dt class="col-sm-3"><?= t('Date Modified') ?></dt>
                                <dd class="col-sm-9"><?= $date->formatPrettyDateTime($block->getBlockDateLastModified()) ?></dd>
                            <?php } ?>
                            <dt class="col-sm-3"><?= t('Cache Lifetime') ?></dt>
                            <dd class="col-sm-9"><?= h($cacheLifetime) ?></dd>
                            <?php if ($block->getBlockFilename()) { ?>
                                <dt class="col-sm-3"><?= h('Template') ?></dt>
                                <dd class="col-sm-9"><?= h($block->getBlockFilename()) ?></dd>
                            <?php } ?>
                            <?php if ($block->getBlockName()) { ?>
                                <dt class="col-sm-3"><?= h('Block Name') ?></dt>
                                <dd class="col-sm-9"><?= h($block->getBlockName()) ?></dd>
                            <?php } ?>
                        </dl>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}
?>
<script>
    $(function () {
        $('.ccm-block-edit.blink').removeClass('blink');
        $('[data-link-block]').on('click', function (e) {
            e.preventDefault();
            var bID = $(this).data('link-block');
            var target = $('[data-block-id="' + bID + '"]');
            $('body, html').animate({scrollTop: target.offset().top - 54});
            target.addClass('blink');
            $(this).closest('.ui-dialog-content').dialog('close');
        })
    })
</script>
