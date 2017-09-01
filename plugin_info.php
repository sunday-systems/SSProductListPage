<?php

class plugin_info{
    /** プラグインコード(必須)：プラグインを識別する為キーで、他のプラグインと重複しない一意な値である必要がありま. */
    static $PLUGIN_CODE       = "SSProductListPage";
    /** プラグイン名(必須)：EC-CUBE上で表示されるプラグイン名. */
    static $PLUGIN_NAME       = "商品一覧ページレイアウト";
    /** プラグインバージョン(必須)：プラグインのバージョン. */
    static $PLUGIN_VERSION    = "1.0.0";
    /** 対応バージョン(必須)：対応するEC-CUBEバージョン. */
    static $COMPLIANT_VERSION = "2.13.5";
    /** 作者(必須)：プラグイン作者. */
    static $AUTHOR            = "株式会社サンディシステムズ";
    /** 説明(必須)：プラグインの説明. */
    static $DESCRIPTION       = "商品一覧ページレイアウト";
    /** プラグインURL：プラグイン毎に設定出来るURL（説明ページなど） */
    static $PLUGIN_SITE_URL   = "https://www.sunday.systems/";
    static $AUTHOR_SITE_URL   = "https://www.sunday.systems/";
    /** クラス名(必須)：プラグインのクラス（拡張子は含まない） */
    static $CLASS_NAME       = "SSProductListPage";
    /** ライセンス */
    static $LICENSE        = "LGPL";
}