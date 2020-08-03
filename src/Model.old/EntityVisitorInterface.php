<?php
/**
 * Interface of a visitor for an entity.
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 */
namespace Ittweb\AccelaSearch\Model;

/**
 * Interface of a visitor for an entity.
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 */
interface EntityVisitorInterface {
    /**
     * Visits a simple product
     *
     * @param Simple $product Product
     */
    public function visitSimple(Simple $product);

    /**
     * Visits a virtual product
     *
     * @param Virtual $virtual Virtual product
     */
    public function visitVirtual(Virtual $virtual);

    
    /**
     * Visits a downloadable product
     *
     * @param Downloadable $downloadable Downloadable product
     */
    public function visitDownloadable(Downloadable $downloadable);

    /**
     * Visits a configurable product
     *
     * @param Configurable $configurable Configurable
     */
    public function visitConfigurable(Configurable $configurable);

    /**
     * Visits a bundle product
     *
     * @param Bundle $bundle Bundle product
     */
    public function visitBundle(Bundle $bundle);

    /**
     * Visits a grouped product
     *
     * @param Grouped $grouped Grouped product
     */
    public function visitGrouped(Grouped $grouped);

    /**
     * Visits a page
     *
     * @param Page $page Page
     */
    public function visitPage(Page $page);

    /**
     * Visits a category page
     *
     * @param Category $category Category page
     */
    public function visitCategory(Category $category);

    /**
     * Visits a banner item
     *
     * @param Banner $banner Banner product
     */
    public function visitBanner(Banner $banner);
}
