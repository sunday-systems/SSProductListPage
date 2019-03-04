<?php

namespace Plugin\SSProductListPage\Entity;

use Eccube\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation as Eccube;

/**
 * PageLayout
 *
 * @ORM\Table(name="dtb_category_layout")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Plugin\SSProductListPage\Repository\CategoryLayoutRepository")
 */
class CategoryLayout extends AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="category_id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $category_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="layout_id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $layout_id;

    /**
     * @var int
     *
     * @ORM\Column(name="sort_no", type="smallint", options={"unsigned":true})
     */
    private $sort_no;

    /**
     * @var \Eccube\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Category", inversedBy="CategoryLayouts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $Category;

    /**
     * @var \Eccube\Entity\Layout
     *
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Layout", inversedBy="CategoryLayouts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="layout_id", referencedColumnName="id")
     * })
     */
    private $Layout;

    /**
     * @return int
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param int $category_id
     * @return CategoryLayout
     */
    public function setCategoryId(int $category_id)
    {
        $this->category_id = $category_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getLayoutId()
    {
        return $this->layout_id;
    }

    /**
     * @param int $layout_id
     * @return CategoryLayout
     */
    public function setLayoutId(int $layout_id)
    {
        $this->layout_id = $layout_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getSortNo()
    {
        return $this->sort_no;
    }

    /**
     * @param int $sort_no
     * @return CategoryLayout
     */
    public function setSortNo(int $sort_no)
    {
        $this->sort_no = $sort_no;
        return $this;
    }

    /**
     * @return \Eccube\Entity\Category
     */
    public function getCategory()
    {
        return $this->Category;
    }

    /**
     * @param \Eccube\Entity\Category $Category
     * @return CategoryLayout
     */
    public function setCategory(\Eccube\Entity\Category $Category)
    {
        $this->Category = $Category;
        return $this;
    }

    /**
     * @return \Eccube\Entity\Layout
     */
    public function getLayout()
    {
        return $this->Layout;
    }

    /**
     * @param \Eccube\Entity\Layout $Layout
     * @return CategoryLayout
     */
    public function setLayout(\Eccube\Entity\Layout $Layout)
    {
        $this->Layout = $Layout;
        return $this;
    }

    /**
     * DeviceTypeがあればDeviceTypeIdを返す
     * DeviceTypeがなければnullを返す
     *
     * @return int|null
     */
    public function getDeviceTypeId()
    {
        if ($this->Layout->getDeviceType()) {
            return $this->Layout->getDeviceType()->getId();
        }

        return null;
    }
}