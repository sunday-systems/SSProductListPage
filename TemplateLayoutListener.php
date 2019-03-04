<?php

namespace Plugin\SSProductListPage;

use Eccube\Entity\Category;
use Eccube\Entity\Master\DeviceType;
use Eccube\Repository\LayoutRepository;
use Eccube\Request\Context;
use Plugin\SSProductListPage\Entity\CategoryLayout;
use SunCat\MobileDetectBundle\DeviceDetector\MobileDetector;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Twig\Environment;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RequestContext;

class TemplateLayoutListener implements EventSubscriberInterface
{

    /**
     * @var bool
     */
    protected $initialized = false;

    /**
     * @var RequestContext
     */
    protected $requestContext;

    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var MobileDetector
     */
    protected $mobileDetector;

    /**
     * @var LayoutRepository
     */
    protected $layoutRepository;

    public function __construct(Environment $twig, Context $requestContext, MobileDetector $mobileDetector, LayoutRepository $layoutRepository)
    {
        $this->requestContext = $requestContext;
        $this->twig = $twig;
        $this->mobileDetector = $mobileDetector;
        $this->layoutRepository = $layoutRepository;
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {

        if ($this->requestContext->isAdmin()) {
            return;
        }

        $request = $event->getRequest();

        /** @var \Symfony\Component\HttpFoundation\ParameterBag $attributes */
        /** @var Page $Page */
        $attributes = $request->attributes;

        if ($attributes->get('_route') == "product_list") {
            $data = $event->getControllerResult();
            if (array_key_exists('Category', $data) && $data['Category'] != null && $data['Category'] instanceof Category) {
                $type = DeviceType::DEVICE_TYPE_PC;
                if ($this->mobileDetector->isMobile()) {
                    $type = DeviceType::DEVICE_TYPE_MB;
                }

                $Layout = null;
                /** @var CategoryLayout $CategoryLayout */
                foreach ($data['Category']->getCategoryLayouts() as $CategoryLayout) {
                    if ($CategoryLayout->getDeviceTypeId() == $type) {
                        $Layout = $CategoryLayout->getLayout();
                        break;
                    }
                }
                if ($Layout) {
                    // lazy loadを制御するため, Layoutを取得しなおす.
                    $Layout = $this->layoutRepository->get($Layout->getId());
                    $this->twig->addGlobal('Layout', $Layout);
                }
            }
        }




        if ($this->initialized) {
            return;
        }

        $this->initialized = true;

        if ($this->requestContext->isAdmin()) {
            return;
        }

        //$this->twig->addGlobal("Layout", "xx");

    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['onKernelView', 1],
            //KernelEvents::RESPONSE => ['onKernelResponse', 1],
        ];
    }
}