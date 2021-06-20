<?php

namespace Concrete\Package\ToolbarBlockOutline\MenuItem\BlockOutline;

use Concrete\Core\Page\Page;
use Concrete\Core\Permission\Checker;
use Concrete\Core\Url\Resolver\Manager\ResolverManagerInterface;

class Controller extends \Concrete\Core\Application\UserInterface\Menu\Item\Controller
{
    public function displayItem()
    {
        $page = Page::getCurrentPage();
        if (is_object($page) && !$page->isError() && !$page->isAdminArea() && !$page->isAliasPageOrExternalLink() && !$page->isSystemPage()) {
            $checker = new Checker($page);
            if ($checker->canEditPageContents()) {
                /** @var ResolverManagerInterface $resolver */
                $resolver = $this->app->make(ResolverManagerInterface::class);
                $this->menuItem->setLink($resolver->resolve(['/ccm/toolbar_block_outline', 'dialog'])->setQuery(['cID' => $page->getCollectionID()]));
                $this->addHeaderItem('<style>.blink{-webkit-animation:blink-2 .9s both;animation:blink .9s both}@-webkit-keyframes blink{0%{opacity:1}50%{opacity:.2}100%{opacity:1}}@keyframes blink{0%{opacity:1}50%{opacity:.2}100%{opacity:1}}</style>');

                return true;
            }
        }

        return false;
    }
}
