<?php

namespace Plugin\SSProductListPage;

use Eccube\Event\TemplateEvent;

class SSProductListPage {

    /**
     * @var \Eccube\Application
     */
    private $app;

    public function __construct(\Eccube\Application $app) 
    {
        $this->app = $app;
    }
    
    public function onRenderTplAdminProductCategory(TemplateEvent $event)
    {
        
        $data = $event->getParameters();
        
        $data['IsExPageLayout'] = false;
        if ($data['Parent']) {
            /* @var $em \Doctrine\ORM\EntityManager */
            $em = $this->app['orm.em'];
            $pageId = $em->getConnection()->fetchAll("SELECT page_id FROM plg_ss_product_list_layout WHERE page_id = ? AND device_type_id = 10", array($data['Parent']->getId()));
            if (count($pageId)) {
                $data['IsExPageLayout'] = true;
            }
        }
        
        $oldMethod = '<div class="extra-form">';
        $source = str_replace($oldMethod, 
            $oldMethod . '{% if Parent %}
                <a class="btn btn-default btn-sm" href="{{ url(\'ss_admin_product_list_layout_edit\', {id: Parent.id}) }}">独自レイアウト</a>
                {% if IsExPageLayout %}<a class="btn btn-default btn-sm" href="{{ url(\'ss_admin_product_list_layout_delete\', {id: Parent.id}) }}" {{ csrf_token_for_anchor() }} data-method="delete" data-message="このレイアウトを削除してもよろしいですか？">レイアウト削除</a>{% endif %}
            {% endif %}', 
            $event->getSource());
        
        $event->setParameters($data);
        $event->setSource($source);
    }
}

