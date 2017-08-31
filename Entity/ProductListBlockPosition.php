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
    private $block_row;

    /**
     * @var integer
     */
    private $anywhere = '0';

    /**
     * @var \Plugin\SSProductListPage\Entity\Block
     */
    private $Block;

    /**
     * @var \Plugin\SSProductListPage\Entity\ProductListLayout
     */
    private $ProductListLayout;


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
     * Set block_row
     *
     * @param integer $blockRow
     * @return ProductListBlockPosition
     */
    public function setBlockRow($blockRow)
    {
        $this->block_row = $blockRow;

        return $this;
    }

    /**
     * Get block_row
     *
     * @return integer 
     */
    public function getBlockRow()
    {
        return $this->block_row;
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
     * @param \Plugin\SSProductListPage\Entity\Block $block
     * @return ProductListBlockPosition
     */
    public function setBlock(\Plugin\SSProductListPage\Entity\Block $block)
    {
        $this->Block = $block;

        return $this;
    }

    /**
     * Get Block
     *
     * @return \Plugin\SSProductListPage\Entity\Block 
     */
    public function getBlock()
    {
        return $this->Block;
    }

    /**
     * Set ProductListLayout
     *
     * @param \Plugin\SSProductListPage\Entity\ProductListLayout $productListLayout
     * @return ProductListBlockPosition
     */
    public function setProductListLayout(\Plugin\SSProductListPage\Entity\ProductListLayout $productListLayout)
    {
        $this->ProductListLayout = $productListLayout;

        return $this;
    }

    /**
     * Get ProductListLayout
     *
     * @return \Plugin\SSProductListPage\Entity\ProductListLayout 
     */
    public function getProductListLayout()
    {
        return $this->ProductListLayout;
    }
}
