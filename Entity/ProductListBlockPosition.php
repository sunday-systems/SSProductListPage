<?php

namespace Plugin\SSProductListPage\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductListBlockPosition
 */
class ProductListBlockPosition extends \Eccube\Entity\AbstractEntity
{
    /**
     * @var integer
     */
    private $page_id;

    /**
     * @var integer
     */
    private $target_id;

    /**
     * @var integer
     */
    private $block_id;

    /**
     * @var integer
     */
    private $blockRow;

    /**
     * @var integer
     */
    private $anywhere;

    /**
     * @var \Eccube\Entity\Block
     */
    private $Block;

    /**
     * @var \Plugin\SSProductListPage\Entity\ProductListLayout
     */
    private $PageLayout;


    /**
     * Set page_id
     *
     * @param integer $pageId
     * @return ProductListBlockPosition
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
     * Set target_id
     *
     * @param integer $targetId
     * @return ProductListBlockPosition
     */
    public function setTargetId($targetId)
    {
        $this->target_id = $targetId;

        return $this;
    }

    /**
     * Get target_id
     *
     * @return integer 
     */
    public function getTargetId()
    {
        return $this->target_id;
    }

    /**
     * Set block_id
     *
     * @param integer $blockId
     * @return ProductListBlockPosition
     */
    public function setBlockId($blockId)
    {
        $this->block_id = $blockId;

        return $this;
    }

    /**
     * Get block_id
     *
     * @return integer 
     */
    public function getBlockId()
    {
        return $this->block_id;
    }

    /**
     * Set blockRow
     *
     * @param integer $blockRow
     * @return ProductListBlockPosition
     */
    public function setBlockRow($blockRow)
    {
        $this->blockRow = $blockRow;

        return $this;
    }

    /**
     * Get blockRow
     *
     * @return integer 
     */
    public function getBlockRow()
    {
        return $this->blockRow;
    }

    /**
     * Set anywhere
     *
     * @param integer $anywhere
     * @return ProductListBlockPosition
     */
    public function setAnywhere($anywhere)
    {
        $this->anywhere = $anywhere;

        return $this;
    }

    /**
     * Get anywhere
     *
     * @return integer 
     */
    public function getAnywhere()
    {
        return $this->anywhere;
    }

    /**
     * Set Block
     *
     * @param \Eccube\Entity\Block $block
     * @return ProductListBlockPosition
     */
    public function setBlock(\Eccube\Entity\Block $block)
    {
        $this->Block = $block;

        return $this;
    }

    /**
     * Get Block
     *
     * @return \Eccube\Entity\Block 
     */
    public function getBlock()
    {
        return $this->Block;
    }

    /**
     * Set PageLayout
     *
     * @param \Plugin\SSProductListPage\Entity\ProductListLayout $pageLayout
     * @return ProductListBlockPosition
     */
    public function setPageLayout(\Plugin\SSProductListPage\Entity\ProductListLayout $pageLayout)
    {
        $this->PageLayout = $pageLayout;

        return $this;
    }

    /**
     * Get PageLayout
     *
     * @return \Plugin\SSProductListPage\Entity\ProductListLayout 
     */
    public function getPageLayout()
    {
        return $this->PageLayout;
    }
}
