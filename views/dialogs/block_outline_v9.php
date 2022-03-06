<?php
defined('C5_EXECUTE') or die('Access Denied.');

$isEditMode = $isEditMode ?? false;
$areaBlocks = $areaBlocks ?? [];
$app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
/** @var \Concrete\Core\Application\Service\Urls $urls */
$urls = $app->make('helper/concrete/urls');
/** @var \Concrete\Core\Utility\Service\Text $text */
$text = $app->make('helper/text');

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
                echo '</a>';
            }
            ?>
        </div>
        <div class="card-body">
                <?php
                /** @var \Concrete\Core\Block\Block $block */
                foreach ($blocks as $block) {
                    /** @var \Concrete\Core\Entity\Block\BlockType\BlockType $blockType */
                    $blockType = $block->getBlockTypeObject();
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
                                <span class="badge bg-<?php if ($block->cacheBlockOutput()) { ?>primary<?php } else { ?>secondary<?php } ?>"><?= h('Cache') ?></span>
                                <span class="badge bg-<?php if ($block->cacheBlockOutputForRegisteredUsers()) { ?>primary<?php } else { ?>secondary<?php } ?>"><?= h('Cache for Registered') ?></span>
                                <span class="badge bg-<?php if ($block->cacheBlockOutputOnPost()) { ?>primary<?php } else { ?>secondary<?php } ?>"><?= h('Cache on Post') ?></span>
                                <span class="badge bg-<?php if ($block->isAlias()) { ?>primary<?php } else { ?>secondary<?php } ?>"><?= h('Alias') ?></span>
                                <span class="badge bg-<?php if ($block->isAliasOfMasterCollection()) { ?>primary<?php } else { ?>secondary<?php } ?>"><?= h('Alias from default') ?></span>
                            </div>
                            <ul class="list-inline text-muted">
                                <li class="list-inline-item"><small>bID: <?= $block->getBlockID() ?></small></li>
                                <?php if ($block->getBlockFilename()) { ?>
                                    <li class="list-inline-item"><small><?= h('Template') ?>: <?= h($block->getBlockFilename()) ?></small></li>
                                <?php } ?>
                                <li class="list-inline-item"><small>Cache Lifetime: <?= $block->getBlockCacheSettingsObject()->getBlockOutputCacheLifetime() ?>s</small></li>
                            </ul>
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
