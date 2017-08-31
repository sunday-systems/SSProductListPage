<?php

namespace Plugin\SSProductListPage\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductListLayout
 */
class ProductListLayout extends \Eccube\Entity\AbstractEntity
{
    // 配置ID
    /** 配置ID: 未使用 */
    const TARGET_ID_UNUSED = 0;
    const TARGET_ID_HEAD = 1;
    const TARGET_ID_HEADER = 2;
    const TARGET_ID_CONTENTS_TOP = 3;
    const TARGET_ID_SIDE_LEFT = 4;
    const TARGET_ID_MAIN_TOP = 5;
    const TARGET_ID_MAIN_BOTTOM = 6;
    const TARGET_ID_SIDE_RIGHT = 7;
    const TARGET_ID_CONTENTS_BOTTOM = 8;
    const TARGET_ID_FOOTER = 9;
    
    // 編集可能フラグ
    const EDIT_FLG_USER = 0;
    const EDIT_FLG_PREVIEW = 1;
    const EDIT_FLG_DEFAULT = 2;
    
    /**
     * Get ColumnNum
     *
     * @return integer
     */
    public function getColumnNum()
    {
        return 1 + ($this->getSideLeft() ? 1 : 0) + ($this->getSideRight() ? 1 : 0);
    }
    
    public function getTheme()
    {
        $hasLeft = $this->getSideLeft() ? true : false;
        $hasRight = $this->getSideRight() ? true : false;
    
        $theme = 'theme_main_only';
        if ($hasLeft && $hasRight) {
            $theme = 'theme_side_both';
        } elseif ($hasLeft) {
            $theme = 'theme_side_left';
        } elseif ($hasRight) {
            $theme = 'theme_side_right';
        }
    
        return $theme;
    }
    
    /**
     * Get BlockPositionByTargetId
     *
     * @param integer $target_id
     * @return \Eccube\Entity\BlockPosition
     */
    public function getBlocksPositionByTargetId($target_id)
    {
        $BlockPositions = array();
        foreach ($this->getBlockPositions() as $BlockPosition) {
            if ($BlockPosition->getTargetId() === $target_id) {
                $BlockPositions[] = $BlockPosition;
            }
        }
    
        return $BlockPositions;
    }
    
    public function getUnusedPosition()
    {
        return $this->getBlocksPositionByTargetId(self::TARGET_ID_UNUSED);
    }
    
    public function getHeadPosition()
    {
        return $this->getBlocksPositionByTargetId(self::TARGET_ID_HEAD);
    }
    
    public function getHeaderPosition()
    {
        return $this->getBlocksPositionByTargetId(self::TARGET_ID_HEADER);
    }
    
    public function getContentsTopPosition()
    {
        return $this->getBlocksPositionByTargetId(self::TARGET_ID_CONTENTS_TOP);
    }
    
    public function getSideLeftPosition()
    {
        return $this->getBlocksPositionByTargetId(self::TARGET_ID_SIDE_LEFT);
    }
    
    public function getMainTopPosition()
    {
        return $this->getBlocksPositionByTargetId(self::TARGET_ID_MAIN_TOP);
    }
    
    public function getMainBottomPosition()
    {
        return $this->getBlocksPositionByTargetId(self::TARGET_ID_MAIN_BOTTOM);
    }
    
    public function getSideRightPosition()
    {
        return $this->getBlocksPositionByTargetId(self::TARGET_ID_SIDE_RIGHT);
    }
    
    public function getContentsBottomPosition()
    {
        return $this->getBlocksPositionByTargetId(self::TARGET_ID_CONTENTS_BOTTOM);
    }
    
    public function getFooterPosition()
    {
        return $this->getBlocksPositionByTargetId(self::TARGET_ID_FOOTER);
    }
    
    /**
     * Get BlocsByTargetId
     *
     * @param integer $target_id
     * @return \Eccube\Entity\Bloc[]
     */
    public function getBlocksByTargetId($target_id)
    {
        $Blocks = array();
        foreach ($this->getBlockPositions() as $BlockPositions) {
            if ($BlockPositions->getTargetId() === $target_id) {
                $Blocks[] = $BlockPositions->getBlock();
            }
        }
        return $Blocks;
    }
    
    public function getUnused()
    {
        return $this->getBlocksByTargetId(self::TARGET_ID_UNUSED);
    }
    
    public function getHead()
    {
        return $this->getBlocksByTargetId(self::TARGET_ID_HEAD);
    }
    
    public function getHeader()
    {
        return $this->getBlocksByTargetId(self::TARGET_ID_HEADER);
    }
    
    public function getContentsTop()
    {
        return $this->getBlocksByTargetId(self::TARGET_ID_CONTENTS_TOP);
    }
    
    public function getSideLeft()
    {
        return $this->getBlocksByTargetId(self::TARGET_ID_SIDE_LEFT);
    }
    
    public function getMainTop()
    {
        return $this->getBlocksByTargetId(self::TARGET_ID_MAIN_TOP);
    }
    
    public function getMainBottom()
    {
        return $this->getBlocksByTargetId(self::TARGET_ID_MAIN_BOTTOM);
    }
    
    public function getSideRight()
    {
        return $this->getBlocksByTargetId(self::TARGET_ID_SIDE_RIGHT);
    }
    
    public function getContentsBottom()
    {
        return $this->getBlocksByTargetId(self::TARGET_ID_CONTENTS_BOTTOM);
    }
    
    public function getFooter()
    {
        return $this->getBlocksByTargetId(self::TARGET_ID_FOOTER);
    }
    
    
    /**
     * @var integer
     */
    private $page_id;

    /**
     * @var integer
     */
    private $deviceTypeId;

    /**
     * @var string
     */
    private $pageName;

    /**
     * @var integer
     */
    private $editFlg;

    /**
     * @var string
     */
    private $author;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $keyword;

    /**
     * @var string
     */
    private $updateUrl;

    /**
     * @var \DateTime
     */
    private $createDate;

    /**
     * @var \DateTime
     */
    private $updateDate;

    /**
     * @var string
     */
    private $metaRobots;

    /**
     * @var string
     */
    private $metaTags;

    /**
     * @var \Eccube\Entity\Product
     */
    private $Product;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $BlockPositions;

    /**
     * @var \Eccube\Entity\Master\DeviceType
     */
    private $DeviceType;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->BlockPositions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set page_id
     *
     * @param integer $pageId
     * @return ProductListLayout
     */
    public function setPageId($pageId)
    {
        $this->page_id = $pageId;

        return $this;
    }

    /**
     * Get page_id
     *
     * @return integer 
     */
    public function getPageId()
    {
        return $this->page_id;
    }

    /**
     * Set deviceTypeId
     *
     * @param integer $deviceTypeId
     * @return ProductListLayout
     */
    public function setDeviceTypeId($deviceTypeId)
    {
        $this->deviceTypeId = $deviceTypeId;

        return $this;
    }

    /**
     * Get deviceTypeId
     *
     * @return integer 
     */
    public function getDeviceTypeId()
    {
        return $this->deviceTypeId;
    }

    /**
     * Set pageName
     *
     * @param string $pageName
     * @return ProductListLayout
     */
    public function setPageName($pageName)
    {
        $this->pageName = $pageName;

        return $this;
    }

    /**
     * Get pageName
     *
     * @return string 
     */
    public function getPageName()
    {
        return $this->pageName;
    }

    /**
     * Set editFlg
     *
     * @param integer $editFlg
     * @return ProductListLayout
     */
    public function setEditFlg($editFlg)
    {
        $this->editFlg = $editFlg;

        return $this;
    }

    /**
     * Get editFlg
     *
     * @return integer 
     */
    public function getEditFlg()
    {
        return $this->editFlg;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return ProductListLayout
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ProductListLayout
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set keyword
     *
     * @param string $keyword
     * @return ProductListLayout
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * Get keyword
     *
     * @return string 
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Set updateUrl
     *
     * @param string $updateUrl
     * @return ProductListLayout
     */
    public function setUpdateUrl($updateUrl)
    {
        $this->updateUrl = $updateUrl;

        return $this;
    }

    /**
     * Get updateUrl
     *
     * @return string 
     */
    public function getUpdateUrl()
    {
        return $this->updateUrl;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return ProductListLayout
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime 
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     * @return ProductListLayout
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime 
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Set metaRobots
     *
     * @param string $metaRobots
     * @return ProductListLayout
     */
    public function setMetaRobots($metaRobots)
    {
        $this->metaRobots = $metaRobots;

        return $this;
    }

    /**
     * Get metaRobots
     *
     * @return string 
     */
    public function getMetaRobots()
    {
        return $this->metaRobots;
    }

    /**
     * Set metaTags
     *
     * @param string $metaTags
     * @return ProductListLayout
     */
    public function setMetaTags($metaTags)
    {
        $this->metaTags = $metaTags;

        return $this;
    }

    /**
     * Get metaTags
     *
     * @return string 
     */
    public function getMetaTags()
    {
        return $this->metaTags;
    }

    /**
     * Set Product
     *
     * @param \Eccube\Entity\Product $product
     * @return ProductListLayout
     */
    public function setProduct(\Eccube\Entity\Product $product = null)
    {
        $this->Product = $product;

        return $this;
    }

    /**
     * Get Product
     *
     * @return \Eccube\Entity\Product 
     */
    public function getProduct()
    {
        return $this->Product;
    }

    /**
     * Add BlockPositions
     *
     * @param \Plugin\SSProductListPage\Entity\ProductListBlockPosition $blockPositions
     * @return ProductListLayout
     */
    public function addBlockPosition(\Plugin\SSProductListPage\Entity\ProductListBlockPosition $blockPositions)
    {
        $this->BlockPositions[] = $blockPositions;

        return $this;
    }

    /**
     * Remove BlockPositions
     *
     * @param \Plugin\SSProductListPage\Entity\ProductListBlockPosition $blockPositions
     */
    public function removeBlockPosition(\Plugin\SSProductListPage\Entity\ProductListBlockPosition $blockPositions)
    {
        $this->BlockPositions->removeElement($blockPositions);
    }

    /**
     * Get BlockPositions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBlockPositions()
    {
        return $this->BlockPositions;
    }

    /**
     * Set DeviceType
     *
     * @param \Eccube\Entity\Master\DeviceType $deviceType
     * @return ProductListLayout
     */
    public function setDeviceType(\Eccube\Entity\Master\DeviceType $deviceType = null)
    {
        $this->DeviceType = $deviceType;

        return $this;
    }

    /**
     * Get DeviceType
     *
     * @return \Eccube\Entity\Master\DeviceType 
     */
    public function getDeviceType()
    {
        return $this->DeviceType;
    }
}
