<?php

namespace Concrete\Package\ToolbarBlockOutline;

use Concrete\Core\Application\Service\UserInterface\Menu;
use Concrete\Core\Package\Package;
use Concrete\Core\Routing\Router;
use Concrete\Core\Routing\RouterInterface;

class Controller extends Package
{
    protected $appVersionRequired = '8.5.5';
    protected $pkgHandle = 'toolbar_block_outline';
    protected $pkgVersion = '0.0.1';

    public function getPackageName()
    {
        return t('Toolbar Block Outline');
    }

    public function getPackageDescription()
    {
        return t('Add a button to show outline of blocks in toolbar.');
    }

    public function on_start()
    {
        /** @var Router $router */
        $router = $this->app->make(RouterInterface::class);
        $router->buildGroup()->setPrefix('/ccm/toolbar_block_outline')
            ->setNamespace('Concrete\Package\ToolbarBlockOutline\Controller')
            ->routes('menu.php', $this->getPackageHandle());

        /** @var Menu $menu */
        $menu = $this->app->make('helper/concrete/ui/menu');
        $menu->addPageHeaderMenuItem('block_outline', $this->getPackageHandle(), [
            'icon' => 'bars',
            'label' => t('Outline'),
            'position' => 'left',
            'linkAttributes' => [
                'class' => 'dialog-launch launch-tooltip',
                'dialog-width' => 640,
                'dialog-height' => 640,
                'dialog-title' => t('Outline'),
                'data-toggle' => 'tooltip',
                'data-placement' => 'bottom',
                'data-original-title' => t('Outline'),
            ],
        ]);
    }
}
