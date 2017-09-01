<?php

define('PLUGIN_SS_PRODUCTLIST_DIR', dirname(__FILE__) . '/');
define('PLUGIN_SS_PRODUCTLIST_DATA', PLUGIN_SS_PRODUCTLIST_DIR . 'data/');
define('PLUGIN_SS_PRODUCTLIST_PAGE', PLUGIN_SS_PRODUCTLIST_DIR . 'data/page/');
define('PLUGIN_SS_PRODUCTLIST_TPL', PLUGIN_SS_PRODUCTLIST_DIR . 'data/templates/');
define('PLUGIN_SS_PRODUCTLIST_HEL', PLUGIN_SS_PRODUCTLIST_DIR . 'data/helper/');

require_once PLUGIN_SS_PRODUCTLIST_HEL . 'SS_Helper_ProcucListPageLayout_Ex.php';

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
        
        copy(PLUGIN_UPLOAD_REALDIR . "SSProductListPage/html/admin/design/ss_product_list.php", HTML_REALDIR . "admin/design/ss_product_list.php");
        
        $createSql = array();
        
        SC_Query_Ex::getSingletonInstance()->begin();
        
        if (DB_TYPE == 'pgsql') {
            $createSql[] = "CREATE TABLE plg_product_list_pagelayout (
                device_type_id integer NOT NULL,
                page_id integer NOT NULL,
                header_chk smallint DEFAULT 1,
                footer_chk smallint DEFAULT 1,
                update_url text,
                create_date timestamp without time zone DEFAULT now() NOT NULL,
                update_date timestamp without time zone NOT NULL,
                meta_robots text
            )";
            
            $createSql[] = "ALTER TABLE ONLY plg_product_list_pagelayout ADD CONSTRAINT plg_product_list_pagelayout_pkey PRIMARY KEY (device_type_id, page_id)";
            
            $createSql[] = "CREATE TABLE plg_product_list_blocposition (
                device_type_id integer NOT NULL,
                page_id integer NOT NULL,
                target_id integer NOT NULL,
                bloc_id integer NOT NULL,
                bloc_row integer,
                anywhere smallint DEFAULT 0 NOT NULL
            )";
            
            $createSql[] = "ALTER TABLE ONLY plg_product_list_blocposition ADD CONSTRAINT plg_product_list_blocposition_pkey PRIMARY KEY (device_type_id, page_id, target_id, bloc_id)";
            
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
    
        $deleteSql[] = "DROP TABLE plg_product_list_pagelayout";
        $deleteSql[] = "DROP TABLE plg_product_list_blocposition";
        
        foreach ($deleteSql as $sql) {
            SC_Query_Ex::getSingletonInstance()->exec($sql);
        }
        
        SC_Query_Ex::getSingletonInstance()->commit();
    }
    
    function register(SC_Helper_Plugin $objHelperPlugin) 
    {
        $objHelperPlugin->addAction('prefilterTransform', array(&$this, 'prefilterTransform'));
        $objHelperPlugin->addAction('LC_Page_Admin_Products_Category_action_after', array(&$this, 'pageData'));
        $objHelperPlugin->addAction('LC_Page_Products_List_action_after', array(&$this, 'pageList'));
    }
    
    function enable($arrPlugin) 
    {
    }

    function disable($arrPlugin) 
    {
    }
    
    /**
     * プレフィルタコールバック関数
     *
     * @param string &$source テンプレートのHTMLソース
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @param string $filename テンプレートのファイル名
     * @return void
     */
    function prefilterTransform(&$source, LC_Page_Ex $objPage, $filename) 
    {
        $objTransform = new SC_Helper_Transform($source);
        switch($objPage->arrPageLayout['device_type_id']){
            case DEVICE_TYPE_MOBILE:
            case DEVICE_TYPE_SMARTPHONE:
            case DEVICE_TYPE_PC:
            case DEVICE_TYPE_ADMIN:
            default:
                
                // カテゴリ登録画面
                if (strpos($filename, 'products/category.tpl') !== false) {
                    
                    $addHtml = '<!--{if $SSisEditPageData}-->
            	<a class="btn-normal" href="../design/ss_product_list.php?device_type_id=<!--{$smarty.const.DEVICE_TYPE_PC}-->&page_id=<!--{$arrForm.parent_category_id|h}-->">
                	<span class="btn-next">PCレイアウト登録</span>
                </a>
                
                <!--{if $SSisExPc}-->
                	<a class="btn-normal" onclick="return window.confirm(\'一度削除したデータは、元に戻せません。\n削除しても宜しいですか？\')" href="../design/ss_product_list.php?device_type_id=<!--{$smarty.const.DEVICE_TYPE_PC}-->&page_id=<!--{$arrForm.parent_category_id|h}-->&mode=delete&<!--{$smarty.const.TRANSACTION_ID_NAME}-->=<!--{$transactionid}-->">
                        <span class="btn-next">PCレイアウト削除</span>
                    </a>
                <!--{/if}-->
                
                <br /><br />
                <a class="btn-normal" href="../design/ss_product_list.php?device_type_id=<!--{$smarty.const.DEVICE_TYPE_MOBILE}-->&page_id=<!--{$arrForm.parent_category_id|h}-->">
                	<span class="btn-next">モバイルレイアウト登録</span>
                </a>
                
                <!--{if $SSisExMo}-->
                	<a class="btn-normal" onclick="return window.confirm(\'一度削除したデータは、元に戻せません。\n削除しても宜しいですか？\')" href="../design/ss_product_list.php?device_type_id=<!--{$smarty.const.DEVICE_TYPE_MOBILE}-->&page_id=<!--{$arrForm.parent_category_id|h}-->&mode=delete&<!--{$smarty.const.TRANSACTION_ID_NAME}-->=<!--{$transactionid}-->">
                        <span class="btn-next">モバイルレイアウト削除</span>
                    </a>
                <!--{/if}-->
                
                <br /><br />
                <a class="btn-normal" href="../design/ss_product_list.php?device_type_id=<!--{$smarty.const.DEVICE_TYPE_SMARTPHONE}-->&page_id=<!--{$arrForm.parent_category_id|h}-->">
                	<span class="btn-next">スマートレイアウト登録</span>
                </a>
                
                <!--{if $SSisExSP}-->
                	<a class="btn-normal" onclick="return window.confirm(\'一度削除したデータは、元に戻せません。\n削除しても宜しいですか？\')" href="../design/ss_product_list.php?device_type_id=<!--{$smarty.const.DEVICE_TYPE_SMARTPHONE}-->&page_id=<!--{$arrForm.parent_category_id|h}-->&mode=delete&<!--{$smarty.const.TRANSACTION_ID_NAME}-->=<!--{$transactionid}-->">
                        <span class="btn-next">スマートレイアウト削除</span>
                    </a>
                <!--{/if}-->
                
                <br /><br />
            <!--{/if}-->';
                    
                    $objTransform->select('div.now_dir')->insertAfter($addHtml);
                }
                break;
        }
        $source = $objTransform->getHTML();
    }
    
    function pageData($objPage)
    {
        $cid = intval($_REQUEST['parent_category_id']);
        $objPage->SSisEditPageData = false;
        $objPage->SSisExPc = false;
        $objPage->SSisExMo = false;
        $objPage->SSisExSP = false;
        
        if ($cid) {
            $objPage->SSisEditPageData = true;
            if (SC_Query_Ex::getSingletonInstance()->exists('plg_product_list_pagelayout', 
                'page_id = ? AND device_type_id = ?', 
                array($cid, DEVICE_TYPE_PC))
            ) {    
                $objPage->SSisExPc = true;
            }
            
            if (SC_Query_Ex::getSingletonInstance()->exists('plg_product_list_pagelayout',
                'page_id = ? AND device_type_id = ?',
                array($cid, DEVICE_TYPE_MOBILE))
            ) {
                $objPage->SSisExMo = true;
            }
            
            if (SC_Query_Ex::getSingletonInstance()->exists('plg_product_list_pagelayout',
                'page_id = ? AND device_type_id = ?',
                array($cid, DEVICE_TYPE_SMARTPHONE))
            ) {
                $objPage->SSisExSP = true;
            }
        }
    }
    
    function pageList($objPage) 
    {
        $cid = intval($_REQUEST['category_id']);
        if ($cid) {
            
            if ($_REQUEST['preview'] && $_SESSION['preview'] == 'ON') {
                $cid = 0;
            }
            
            $isExPageData = SC_Query_Ex::getSingletonInstance()->exists('plg_product_list_pagelayout', 
                'page_id = ? AND device_type_id = ?', 
                array($cid, $objPage->arrPageLayout['device_type_id']));
            
            if ($isExPageData) {
                $arrBlocs = SS_Helper_ProcucListPageLayout_Ex::getBlocPositions($objPage->arrPageLayout['device_type_id'], $cid);
                // 無効なプラグインのブロックを取り除く.
                $objPlugin = SC_Helper_Plugin_Ex::getSingletonInstance();
                
                // 該当ページのブロックを取得し, 配置する
                $masterData = new SC_DB_MasterData_Ex();
                $arrTarget = $masterData->getMasterData('mtb_target');
                $arrBlocs = $objPlugin->getEnableBlocs($arrBlocs);
                
                // php_path, tpl_path が存在するものを, 各ターゲットに配置
                foreach ($arrTarget as $target_id => $value) {
                    $objPage->arrPageLayout[$arrTarget[$target_id]] = array();
                    
                    foreach ($arrBlocs as $arrBloc) {
                        if ($arrBloc['target_id'] != $target_id) {
                            continue;
                        }
                        if (is_file($arrBloc['php_path'])
                            || is_file($arrBloc['tpl_path'])) {
                                $objPage->arrPageLayout[$arrTarget[$target_id]][] = $arrBloc;
                            } else {
                                $error = "ブロックが見つかりません\n"
                                    . 'tpl_path: ' . $arrBloc['tpl_path'] . "\n"
                                        . 'php_path: ' . $arrBloc['php_path'];
                                        trigger_error($error, E_USER_WARNING);
                            }
                    }
                }
                // カラム数を取得する
                $objPage->tpl_column_num = SS_Helper_ProcucListPageLayout_Ex::getColumnNum($objPage->arrPageLayout);
            }
        }
        
    }
}

