<?php

namespace Plugin\SSProductListPage;

use Doctrine\ORM\EntityManager;
use Eccube\Entity\Category;
use Eccube\Entity\Layout;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Event\TemplateEvent;
use Eccube\Form\Type\Admin\CategoryType;
use Eccube\Repository\CategoryRepository;
use Plugin\SSProductListPage\Entity\CategoryLayout;
use Plugin\SSProductListPage\Repository\CategoryLayoutRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactory;

class SSProductListPageEvent implements EventSubscriberInterface
{

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var CategoryLayoutRepository
     */
    protected $categoryLayoutRepository;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var FormFactory
     */
    protected $formFactory;

    public function __construct(EntityManager $entityManager, CategoryLayoutRepository $categoryLayoutRepository, CategoryRepository $categoryRepository, FormFactory $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->categoryLayoutRepository = $categoryLayoutRepository;
        $this->categoryRepository = $categoryRepository;
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            '@admin/Product/category.twig' => ['onTemplateAdminProductCategory', 10],
            EccubeEvents::ADMIN_PRODUCT_CATEGORY_INDEX_COMPLETE => ['onAdminProductCategoryEdit', 10],
            EccubeEvents::ADMIN_PRODUCT_CATEGORY_DELETE_COMPLETE => ['onAdminProductCategoryDelete', 10],
        ];
    }

    /**
     * @param TemplateEvent $templateEvent
     */
    public function onTemplateAdminProductCategory(TemplateEvent $templateEvent)
    {
        $templateEvent->addSnippet('@SSProductListPage/admin/Product/category.twig');
    }

    /**
     * @param EventArgs $eventArgs
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onAdminProductCategoryEdit(EventArgs $eventArgs)
    {
        /** @var \Symfony\Component\Form\Form $form */
        //$form = $eventArgs->getArgument("form");
        /** @var Category $Category */
        $Category = $eventArgs->getArgument("TargetCategory");
        $form = $this->formFactory
            ->createNamed('category_'.$Category->getId(), CategoryType::class, $Category);
        $form->handleRequest($eventArgs->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($Category->getCategoryLayouts() as $CategoryLayout) {
                $Category->removeCategoryLayout($CategoryLayout);
                $this->entityManager->remove($CategoryLayout);
                $this->entityManager->flush($CategoryLayout);
            }

            /** @var Layout $Layout */
            $Layout = $form['PcLayout']->getData();
            $LastPageLayout = $this->categoryLayoutRepository->findOneBy([], ['sort_no' => 'DESC']);
            if ($LastPageLayout == null) {
                $sortNo = 0;
            } else {
                $sortNo = $LastPageLayout->getSortNo();
            }

            if ($Layout) {
                $PageLayout = new CategoryLayout();
                $PageLayout->setLayoutId($Layout->getId());
                $PageLayout->setLayout($Layout);
                $PageLayout->setCategoryId($Category->getId());
                $PageLayout->setSortNo($sortNo++);
                $PageLayout->setCategory($Category);

                $this->entityManager->persist($PageLayout);
                $this->entityManager->flush($PageLayout);

                $Category->addCategoryLayout($PageLayout);
            }

            $Layout = $form['SpLayout']->getData();
            if ($Layout) {
                $PageLayout = new CategoryLayout();
                $PageLayout->setLayoutId($Layout->getId());
                $PageLayout->setLayout($Layout);
                $PageLayout->setCategoryId($Category->getId());
                $PageLayout->setSortNo($sortNo++);
                $PageLayout->setCategory($Category);

                $this->entityManager->persist($PageLayout);
                $this->entityManager->flush($PageLayout);

                $Category->addCategoryLayout($PageLayout);
            }

            $this->entityManager->persist($Category);
            $this->entityManager->flush($Category);
        }
    }

    /**
     * @param EventArgs $eventArgs
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onAdminProductCategoryDelete(EventArgs $eventArgs)
    {

    }
}