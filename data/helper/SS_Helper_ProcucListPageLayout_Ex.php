<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2014 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

/**
 * Webページのレイアウト情報を制御するヘルパークラス.
 *
 * @package Helper
 * @author LOCKON CO.,LTD.
 * @version $Id:SC_Helper_PageLayout.php 15532 2007-08-31 14:39:46Z nanasess $
 */
class SS_Helper_ProcucListPageLayout_Ex
{

    /**
     * ページの属性を取得する.
     *
     * この関数は, dtb_pagelayout の情報を検索する.
     * $device_type_id は必須. デフォルト値は DEVICE_TYPE_PC.
     * $page_id が null の場合は, $page_id が 0 以外のものを検索する.
     *
     * @access public
     * @param  integer $device_type_id 端末種別ID
     * @param  integer $page_id        ページID; null の場合は, 0 以外を検索する.
     * @param  string  $where          追加の検索条件
     * @param  string[]   $arrParams      追加の検索パラメーター
     * @return array   ページ属性の配列
     */
    static public function getPageProperties($device_type_id = DEVICE_TYPE_PC, $page_id = null, $where = '', $arrParams = array())
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $where = 'device_type_id = ? ' . (SC_Utils_Ex::isBlank($where) ? $where : 'AND ' . $where);
        if ($page_id === null) {
            $where = 'page_id <> ? AND ' . $where;
            $page_id = 0;
        } else {
            $where = 'page_id = ? AND ' . $where;
        }
        $objQuery->setOrder('page_id');
        $arrParams = array_merge(array($page_id, $device_type_id), $arrParams);

        return $objQuery->select('*', 'plg_product_list_pagelayout', $where, $arrParams);
    }

    /**
     * ブロック情報を取得する.
     *
     * @access public
     * @param  integer $device_type_id 端末種別ID
     * @param  string  $where          追加の検索条件
     * @param  array   $arrParams      追加の検索パラメーター
     * @param  boolean $has_realpath   php_path, tpl_path の絶対パスを含める場合 true
     * @return array   ブロック情報の配列
     */
    static public function getBlocs($device_type_id = DEVICE_TYPE_PC, $where = '', $arrParams = array(), $has_realpath = true)
    {
        $objBloc = new SC_Helper_Bloc_Ex($device_type_id);
        $arrBlocs = $objBloc->getWhere($where, $arrParams);
        if ($has_realpath) {
            SS_Helper_ProcucListPageLayout_Ex::setBlocPathTo($device_type_id, $arrBlocs);
        }

        return $arrBlocs;
    }

    /**
     * ブロック配置情報を取得する.
     *
     * @access public
     * @param  integer $device_type_id 端末種別ID
     * @param  integer $page_id        ページID
     * @param  boolean $has_realpath   php_path, tpl_path の絶対パスを含める場合 true
     * @return array   配置情報を含めたブロックの配列
     */
    static public function getBlocPositions($device_type_id, $page_id, $has_realpath = true)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $table = <<< __EOF__
        plg_product_list_blocposition AS pos
            JOIN dtb_bloc AS bloc
                ON bloc.bloc_id = pos.bloc_id
                    AND bloc.device_type_id = pos.device_type_id
__EOF__;
        $where = 'bloc.device_type_id = ? AND ((anywhere = 1 AND pos.page_id != 0) OR pos.page_id = ?)';
        $objQuery->setOrder('target_id, bloc_row');
        $arrBlocs = $objQuery->select('*', $table, $where, array($device_type_id, $page_id));
        if ($has_realpath) {
            SS_Helper_ProcucListPageLayout_Ex::setBlocPathTo($device_type_id, $arrBlocs);
        }

        //全ページ設定と各ページのブロックの重複を削除
        $arrUniqBlocIds = array();
        foreach ($arrBlocs as $index => $arrBloc) {
            if ($arrBloc['anywhere'] == 1) {
                $arrUniqBlocIds[] = $arrBloc['bloc_id'];
            }
        }
        foreach ($arrBlocs as $bloc_index => $arrBlocData) {
            if (in_array($arrBlocData['bloc_id'], $arrUniqBlocIds) && $arrBlocData['anywhere'] == 0) {
                unset($arrBlocs[$bloc_index]);
            }
        }

        return $arrBlocs;
    }

    /**
     * ページ情報を削除する.
     *
     * XXX ファイルを確実に削除したかどうかのチェック
     *
     * @access public
     * @param  integer $page_id        ページID
     * @param  integer $device_type_id 端末種別ID
     * @return integer 削除数
     */
    static public function lfDelPageData($page_id, $device_type_id = DEVICE_TYPE_PC)
    {
        // page_id が空でない場合にはdeleteを実行
        if ($page_id != '') {
            SC_Query_Ex::getSingletonInstance()->begin();
            SC_Query_Ex::getSingletonInstance()->delete('plg_product_list_blocposition', 'device_type_id = ? AND page_id = ?', array($device_type_id, $page_id));
            SC_Query_Ex::getSingletonInstance()->delete('plg_product_list_pagelayout', 'device_type_id = ? AND page_id = ?', array($device_type_id, $page_id));
            SC_Query_Ex::getSingletonInstance()->commit();
        }
    }


    /**
     * ブロックの php_path, tpl_path を設定する.
     *
     * @access private
     * @param  integer $device_type_id 端末種別ID
     * @param  array   $arrBlocs       設定するブロックの配列
     * @return void
     */
    static public function setBlocPathTo($device_type_id = DEVICE_TYPE_PC, &$arrBlocs = array())
    {
        foreach ($arrBlocs as $key => $value) {
            $arrBloc =& $arrBlocs[$key];
            $arrBloc['php_path'] = SC_Utils_Ex::isBlank($arrBloc['php_path']) ? '' : HTML_REALDIR . $arrBloc['php_path'];
            $bloc_dir = SS_Helper_ProcucListPageLayout_Ex::getTemplatePath($device_type_id) . BLOC_DIR;
            $arrBloc['tpl_path'] = SC_Utils_Ex::isBlank($arrBloc['tpl_path']) ? '' : $bloc_dir . $arrBloc['tpl_path'];
        }
    }

    /**
     * カラム数を取得する.
     *
     * @access private
     * @param  array   $arrPageLayout レイアウト情報の配列
     * @return integer $col_num カラム数
     */
    static public function getColumnNum($arrPageLayout)
    {
        // メインは確定
        $col_num = 1;
        // LEFT NAVI
        if (count($arrPageLayout['LeftNavi']) > 0) $col_num++;
        // RIGHT NAVI
        if (count($arrPageLayout['RightNavi']) > 0) $col_num++;
        return $col_num;
    }
    
    /**
     * テンプレートのパスを取得する.
     *
     * @access public
     * @param  integer $device_type_id 端末種別ID
     * @param  boolean $isUser         USER_REALDIR 以下のパスを返す場合 true
     * @return string  テンプレートのパス
     */
    static public function getTemplatePath($device_type_id = DEVICE_TYPE_PC, $isUser = false)
    {
        $templateName = '';
        switch ($device_type_id) {
            case DEVICE_TYPE_MOBILE:
                $dir = MOBILE_TEMPLATE_REALDIR;
                $templateName = MOBILE_TEMPLATE_NAME;
                break;
    
            case DEVICE_TYPE_SMARTPHONE:
                $dir = SMARTPHONE_TEMPLATE_REALDIR;
                $templateName = SMARTPHONE_TEMPLATE_NAME;
                break;
    
            case DEVICE_TYPE_PC:
            default:
                $dir = TEMPLATE_REALDIR;
                $templateName = TEMPLATE_NAME;
                break;
        }
        $userPath = USER_REALDIR;
        if ($isUser) {
            $dir = $userPath . USER_PACKAGE_DIR . $templateName . '/';
        }
    
        return $dir;
    }
}
