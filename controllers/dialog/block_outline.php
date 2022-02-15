<?php

namespace Concrete\Package\ToolbarBlockOutline\Controller\Dialog;

use Concrete\Controller\Backend\UserInterface\Page;
use Concrete\Core\Page\Stack\Stack;
use Concrete\Core\Url\Resolver\Manager\ResolverManagerInterface;

class BlockOutline extends Page
{
    protected $viewPath = '/dialogs/block_outline';

    public function view()
    {
        /** @var ResolverManagerInterface $resolver */
        $resolver = $this->app->make(ResolverManagerInterface::class);
        /** @var \Concrete\Core\Page\Page $page */
        $page = $this->page;
        $areaBlocks = [];
        $blocks = $page->getBlocks();
        foreach ($blocks as $block) {
            $areaBlocks[$block->getAreaHandle()]['blocks'][] = $block;
        }
        $globalBlocks = $page->getGlobalBlocks();
        foreach ($globalBlocks as $block) {
            $stackID = $block->getBlockCollectionID();
            $stack = Stack::getByID($stackID);
            $stackName = t('Sitewide %s', $stack->getStackName());
            $areaBlocks[$stackName]['link'] = $resolver->resolve(['/dashboard/blocks/stacks', 'view_details', $stackID]);
            $areaBlocks[$stackName]['blocks'][] = $block;
        }
        $this->set('areaBlocks', $areaBlocks);
        $this->set('isEditMode', $page->isEditMode());
    }

    protected function canAccess()
    {
        return $this->permissions->canEditPageContents();
    }
}
