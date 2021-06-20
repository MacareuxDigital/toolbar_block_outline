<?php
defined('C5_EXECUTE') or die('Access Denied.');

$isEditMode = $isEditMode ?? false;
$areaBlocks = $areaBlocks ?? [];
$app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
/** @var \Concrete\Core\Application\Service\Urls $urls */
$urls = $app->make('helper/concrete/urls');
/** @var \Concrete\Core\Utility\Service\Text $text */
$text = $app->make('helper/text');

foreach ($areaBlocks as $areaHandle => $blocks) {
?>
<div class="panel panel-default">
    <div class="panel-heading"><?= h($areaHandle) ?></div>
    <div class="panel-body">
        <ul class="media-list">
            <?php
            /** @var \Concrete\Core\Block\Block $block */
            foreach ($blocks as $block) {
                /** @var \Concrete\Core\Entity\Block\BlockType\BlockType $blockType */
                $blockType = $block->getBlockTypeObject();
                ?>
                <li class="media">
                    <div class="media-left">
                        <img src="<?= $urls->getBlockTypeIconURL($blockType) ?>" class="media-object"
                             alt="<?= h($blockType->getBlockTypeName()) ?>" width="24" height="24">
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">
                            <?php if ($isEditMode) { ?>
                                <a href="#" data-link-block="<?= h($block->getBlockID()) ?>"><?= h($blockType->getBlockTypeName()) ?></a>
                            <?php } else { ?>
                                <?= h($blockType->getBlockTypeName()) ?>
                            <?php } ?>
                        </h4>
                        <?php
                        if ($block->getBlockTypeHandle() === 'content') {
                            $content = $block->getController()->getContent();
                            ?>
                            <p><?= h($text->shorten(htmLawed($content))) ?></p>
                        <?php
                        }
                        ?>
                        <div>
                            <span class="label label-<?php if ($block->cacheBlockOutput()) { ?>primary<?php } else { ?>default<?php } ?>"><?= h('Cache') ?></span>
                            <span class="label label-<?php if ($block->cacheBlockOutputOnPost()) { ?>primary<?php } else { ?>default<?php } ?>"><?= h('Cache on Post') ?></span>
                            <span class="label label-<?php if ($block->cacheBlockOutputForRegisteredUsers()) { ?>primary<?php } else { ?>default<?php } ?>"><?= h('Cache for Registered') ?></span>
                            <span class="label label-<?php if ($block->isAlias()) { ?>primary<?php } else { ?>default<?php } ?>"><?= h('Alias') ?></span>
                        </div>
                    </div>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>
<?php
}
?>
<script>
    $(function () {
        $('.blink').removeClass('blink');
        $('[data-link-block]').on('click', function (e) {
            e.preventDefault();
            var bID = $(this).data('link-block');
            var target = $('[data-block-id="' + bID + '"]');
            $('body, html').animate({ scrollTop: target.offset().top - 54 });
            target.addClass('blink');
            $(this).closest('.ui-dialog-content').dialog('close');
        })
    })
</script>
