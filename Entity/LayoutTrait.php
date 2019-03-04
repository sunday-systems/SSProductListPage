<?php

namespace Plugin\SSProductListPage\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation as Eccube;

/**
 * @Eccube\EntityExtension("Eccube\Entity\Layout")
 */
trait LayoutTrait {
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="\Plugin\SSProductListPage\Entity\CategoryLayout", mappedBy="Layout", cascade={"persist","remove"})
     * @ORM\OrderBy({"sort_no" = "ASC"})
     */
    private $CategoryLayouts;

    public function addCategoryLayout(CategoryLayout $CategoryLayout)
    {
        if ($this->CategoryLayouts == null) {
            $this->CategoryLayouts = new \Doctrine\Common\Collections\ArrayCollection();
        }
        $this->CategoryLayouts[] = $CategoryLayout;

        return $this;
    }

    public function removeCategoryLayout(CategoryLayout $CategoryLayout)
    {
        if ($this->CategoryLayouts == null) {
            $this->CategoryLayouts = new \Doctrine\Common\Collections\ArrayCollection();
        }
        $this->CategoryLayouts->removeElement($CategoryLayout);
    }

    /**
     * Get CategoryLayoutLayouts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategoryLayouts()
    {
        if ($this->CategoryLayouts == null) {
            $this->CategoryLayouts = new \Doctrine\Common\Collections\ArrayCollection();
        }
        return $this->CategoryLayouts;
    }

    /**
     * Check layout can delete or not
     *
     * @return boolean
     */
    public function isDeletable()
    {
        if (!$this->getPageLayouts()->isEmpty()) {
            return false;
        }

        if (!$this->getCategoryLayouts()->isEmpty()) {
            return false;
        }

        return true;
    }
}