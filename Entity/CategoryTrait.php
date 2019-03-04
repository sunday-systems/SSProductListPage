<?php

namespace Plugin\SSProductListPage\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation as Eccube;

/**
 * @Eccube\EntityExtension("Eccube\Entity\Category")
 */
trait CategoryTrait
{
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Plugin\SSProductListPage\Entity\CategoryLayout", mappedBy="Category", cascade={"persist","remove"})
     */
    private $CategoryLayouts;

    /**
     * @return array
     */
    public function getLayouts()
    {
        if ($this->CategoryLayouts == null) {
            $this->CategoryLayouts = new \Doctrine\Common\Collections\ArrayCollection();
        }

        $Layouts = [];
        foreach ($this->CategoryLayouts as $categoryLayout) {
            $Layouts[] = $categoryLayout->getLayout();
        }

        return $Layouts;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection
     */
    public function getCategoryLayouts()
    {
        if ($this->CategoryLayouts == null) {
            $this->CategoryLayouts = new \Doctrine\Common\Collections\ArrayCollection();
        }
        return $this->CategoryLayouts;
    }

    /**
     * @param CategoryLayout $categoryLayout
     * @return $this
     */
    public function addCategoryLayout(CategoryLayout $categoryLayout)
    {
        if ($this->CategoryLayouts == null) {
            $this->CategoryLayouts = new \Doctrine\Common\Collections\ArrayCollection();
        }
        $this->CategoryLayouts[] = $categoryLayout;

        return $this;
    }

    /**
     * @param CategoryLayout $categoryLayout
     * @return $this
     */
    public function removeCategoryLayout(CategoryLayout $categoryLayout)
    {
        if ($this->CategoryLayouts == null) {
            $this->CategoryLayouts = new \Doctrine\Common\Collections\ArrayCollection();
        }
        $this->CategoryLayouts->removeElement($categoryLayout);

        return $this;
    }

    /**
     * @param $layoutId
     *
     * @return null|int
     */
    public function getSortNo($layoutId)
    {
        $CategoryLayouts = $this->getCategoryLayouts();

        /** @var CategoryLayout $categoryLayout */
        foreach ($CategoryLayouts as $categoryLayout) {
            if ($categoryLayout->getLayoutId() == $layoutId) {
                return $categoryLayout->getSortNo();
            }
        }

        return null;
    }
}
