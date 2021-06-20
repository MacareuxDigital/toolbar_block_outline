<?php

namespace Concrete\Package\ToolbarBlockOutline\Controller\Dialog;

use Concrete\Controller\Backend\UserInterface\Page;
use Concrete\Core\Page\Stack\Stack;

class BlockOutline extends Page
{
    protected $viewPath = '/dialogs/block_outline';

    public function view()
    {
        /** @var \Concrete\Core\Page\Page $page */
        $page = $this->page;
        $areaBlocks = [];
        $blocks = $page->getBlocks();
        foreach ($blocks as $block) {
            $areaBlocks[$block->getAreaHandle()][] = $block;
        }
        $globalBlocks = $page->getGlobalBlocks();
        foreach ($globalBlocks as $block) {
            $stack = Stack::getByID($block->getBlockCollectionID());
            $areaBlocks[t('Sitewide %s', $stack->getStackName())][] = $block;
        }
        $this->set('areaBlocks', $areaBlocks);
        $this->set('isEditMode', $page->isEditMode());
    }

    protected function canAccess()
    {
        return $this->permissions->canEditPageContents();
    }
}
