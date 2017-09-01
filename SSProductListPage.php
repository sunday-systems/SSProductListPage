<?php

class SSProductListPage extends SC_Plugin_Base {
    /**
     * コンストラクタ
     * プラグイン情報(dtb_plugin)をメンバ変数をセットします.
     */
    public function __construct(array $arrSelfInfo)
    {
        parent::__construct($arrSelfInfo);
    }
    
    function preProcess(LC_Page_Ex $objPage)
    {
        //この関数をプラグイン内に定義するだけで実行されます。
    }
    
    function process(LC_Page_EX $objPage) 
    {
        //この関数をプラグイン内に定義するだけで実行されます。
    }
    
    function install($arrPlugin) {
        //copy(PLUGIN_UPLOAD_REALDIR . "SSProductListPage/logo.png", PLUGIN_HTML_REALDIR . "SSProductListPage/logo.png");    
        
        $createSql = array();
        
        SC_Query_Ex::getSingletonInstance()->begin();
        
        if (DB_TYPE == 'pgsql') {
            
        } else {
            $createSql[] = "CREATE TABLE `plg_product_list_pagelayout` (
              `device_type_id` int(11) NOT NULL,
              `page_id` int(11) NOT NULL,
              `header_chk` smallint(6) DEFAULT '1',
              `footer_chk` smallint(6) DEFAULT '1',
              `update_url` text,
              `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `update_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
              `meta_robots` text
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            
            $createSql[] = "ALTER TABLE `plg_product_list_pagelayout` ADD PRIMARY KEY (`device_type_id`,`page_id`)";
            
            $createSql[] = "CREATE TABLE `plg_product_list_blocposition` (
              `device_type_id` int(11) NOT NULL,
              `page_id` int(11) NOT NULL,
              `target_id` int(11) NOT NULL,
              `bloc_id` int(11) NOT NULL,
              `bloc_row` int(11) DEFAULT NULL,
              `anywhere` smallint(6) NOT NULL DEFAULT '0'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            
            $createSql[] = "ALTER TABLE `plg_product_list_blocposition` ADD PRIMARY KEY (`device_type_id`,`page_id`,`target_id`,`bloc_id`)";
        }
        
        foreach ($createSql as $sql) {
            SC_Query_Ex::getSingletonInstance()->exec($sql);
        }
        
        SC_Query_Ex::getSingletonInstance()->commit();
    }
    
    function uninstall($arrPlugin) 
    {
        SC_Query_Ex::getSingletonInstance()->begin();
        
        $deleteSql = array();
        
        if (DB_TYPE == 'pgsql') {
        
        } else {
            $deleteSql[] = "DROP TABLE dtb_pagelayout";
            $deleteSql[] = "DROP TABLE dtb_blocposition";
        }
        
        foreach ($deleteSql as $sql) {
            SC_Query_Ex::getSingletonInstance()->exec($sql);
        }
        
        SC_Query_Ex::getSingletonInstance()->commit();
    }
    
    function register(SC_Helper_Plugin $objHelperPlugin) 
    {
    }
    
    function enable($arrPlugin) 
    {
    }

    function disable($arrPlugin) 
    {
    }
}

