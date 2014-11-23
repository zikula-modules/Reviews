<?php
/**
 * Reviews.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package Reviews
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.6.0 (http://modulestudio.de) at Fri Aug 09 17:57:06 CEST 2013.
 */

/**
 * Url router facade implementation class.
 */
class Reviews_RouterFacade extends Reviews_Base_RouterFacade
{
    /**
     * Constructor.
     */
    function __construct()
    {
        $displayDefaultEnding = System::getVar('shorturlsext', '');

        $this->requirements = array(
                'func'          => '\w+',
                'ot'            => '\w+',
                'slug'          => '[^/.]+', // slugs ([^/.]+ = all chars except / and .)
                'displayending' => '(?:' . $displayDefaultEnding . '|xml|pdf|json|kml)',
                'viewending'    => '(?:\.csv|\.rss|\.atom|\.xml|\.pdf|\.json|\.kml)?',
                'id'            => '\d+'
        );

        // initialise and reference router instance
        $this->router = new Zikula_Routing_UrlRouter();

        // add generic routes
        return $this->initUrlRoutes();
    }
    
    /**
     * Initialise the url routes for this application.
     *
     * @return Zikula_Routing_UrlRouterUrlRouter The router instance treating all initialised routes
     */
    protected function initUrlRoutes()
    {
        $fieldRequirements = $this->requirements;
        $isDefaultModule = (System::getVar('shorturlsdefaultmodule', '') == 'Reviews');
    
        $defaults = array();
        $modulePrefix = '';
        if (!$isDefaultModule) {
            $defaults['module'] = 'Reviews';
            $modulePrefix = ':module/';
        }
    
        $defaults['func'] = 'view';
        $viewFolder = 'view';
        // normal views (e.g. orders/ or customers.xml)
        $this->router->set('va', new Zikula_Routing_UrlRoute($modulePrefix . $viewFolder . '/:ot:viewending', $defaults, $fieldRequirements));
    
        // TODO filter views (e.g. /orders/customer/mr-smith.csv)
        // $this->initRouteForEachSlugType('vn', $modulePrefix . $viewFolder . '/:ot/:filterot/', ':viewending', $defaults, $fieldRequirements);
    
        $defaults['func'] = 'display';
        // normal display pages including the group folder corresponding to the object type
        $this->initRouteForEachSlugType('dn', $modulePrefix . ':ot/', ':displayending', $defaults, $fieldRequirements);
    
        // additional rules for the leading object type (where ot is omitted)
        $defaults['ot'] = 'review';
        $this->initRouteForEachSlugType('dl', $modulePrefix . '', ':displayending', $defaults, $fieldRequirements);
    
        return $this->router;
    }

    /**
     * Helper function to route permalinks for different slug types.
     *
     * @param string $prefix
     * @param string $patternStart
     * @param string $patternEnd
     * @param string $defaults
     * @param string $fieldRequirements
     */
    protected function initRouteForEachSlugType($prefix, $patternStart, $patternEnd, $defaults, $fieldRequirements)
    {
        // entities with unique slug (slug only)
        $categoryenabled = ModUtil::getVar('Reviews', 'enablecategorization');
        $cattopermalink = ModUtil::getVar('Reviews', 'addcategorytitletopermalink');
        if ($categoryenabled == 1 && $cattopermalink == 1) {
            $this->router->set($prefix . 'a', new Zikula_Routing_UrlRoute($patternStart . ':cat' . '/' . ':slug' . $patternEnd,        $defaults, $fieldRequirements));
        } else {
            $this->router->set($prefix . 'b', new Zikula_Routing_UrlRoute($patternStart . ':slug' . $patternEnd,        $defaults, $fieldRequirements));
        }
        // entities with non-unique slug (slug and id)
        $this->router->set($prefix . 'c', new Zikula_Routing_UrlRoute($patternStart . ':slug.:id.' . $patternEnd,    $defaults, $fieldRequirements));
        // entities without slug (id)
        $this->router->set($prefix . 'd', new Zikula_Routing_UrlRoute($patternStart . 'id.:id.' . $patternEnd,        $defaults, $fieldRequirements));
    }

    /**
     * Get name of grouping folder for given object type and function.
     *
     * @param string $objectType Name of treated entity type.
     * @param string $func       Name of function.
     *
     * @return string Name of the group folder
     */
    public function getGroupingFolderFromObjectType($objectType, $func)
    {
        // object type will be used as a fallback
        $groupFolder = $objectType;

        if ($func == 'view') {
            switch ($objectType) {
                case 'review':
                    $groupFolder = 'reviews';
                    break;
                default: return '';
            }
        } else if ($func == 'display') {
            switch ($objectType) {
                case 'review':
                    $groupFolder = 'review';
                    break;
                default: return '';
            }
        }

        return $groupFolder;
    }
}
