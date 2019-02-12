<?php

/*
 * Copyright BibLibre, 2016
 *
 * This software is governed by the CeCILL license under French law and abiding
 * by the rules of distribution of free software.  You can use, modify and/ or
 * redistribute the software under the terms of the CeCILL license as circulated
 * by CEA, CNRS and INRIA at the following URL "http://www.cecill.info".
 *
 * As a counterpart to the access to the source code and rights to copy, modify
 * and redistribute granted by the license, users are provided only with a
 * limited warranty and the software's author, the holder of the economic
 * rights, and the successive licensors have only limited liability.
 *
 * In this respect, the user's attention is drawn to the risks associated with
 * loading, using, modifying and/or developing or reproducing the software by
 * the user in light of its specific status of free software, that may mean that
 * it is complicated to manipulate, and that also therefore means that it is
 * reserved for developers and experienced professionals having in-depth
 * computer knowledge. Users are therefore encouraged to load and test the
 * software's suitability as regards their requirements in conditions enabling
 * the security of their systems and/or data to be ensured and, more generally,
 * to use and operate it in the same conditions as regards security.
 *
 * The fact that you are presently reading this means that you have had
 * knowledge of the CeCILL license and that you accept its terms.
 */

namespace Search\Api\Representation;

use Omeka\Api\Representation\AbstractEntityRepresentation;

class SearchPageRepresentation extends AbstractEntityRepresentation
{
    public function getJsonLdType()
    {
        return 'o:SearchPage';
    }

    public function getJsonLd()
    {
        $entity = $this->resource;
        return [
            'o:name' => $entity->getName(),
            'o:path' => $entity->getPath(),
            'o:index_id' => $entity->getIndex()->getId(),
            'o:form' => $entity->getFormAdapter(),
            'o:settings' => $entity->getSettings(),
            'o:created' => $this->getDateTime($entity->getCreated()),
        ];
    }

    public function adminUrl($action = null, $canonical = false)
    {
        $url = $this->getViewHelper('Url');
        $params = [
            'action' => $action,
            'id' => $this->id(),
        ];
        $options = [
            'force_canonical' => $canonical,
        ];

        return $url('admin/search/page-id', $params, $options);
    }

    /**
     * @return string
     */
    public function adminSearchUrl($canonical = false)
    {
        $url = $this->getViewHelper('Url');
        $options = [
            'force_canonical' => $canonical,
        ];
        return $url('search-admin-page-' . $this->id(), [], $options);
    }

    public function siteUrl($siteSlug = null, $canonical = false)
    {
        $url = $this->getViewHelper('Url');
        $params = [
            'site-slug' => $siteSlug,
        ];
        $options = [
            'force_canonical' => $canonical,
        ];

        return $url('search-page-' . $this->id(), $params, $options);
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->resource->getName();
    }

    /**
     * @return string
     */
    public function path()
    {
        return $this->resource->getPath();
    }

    /**
     * @return \Search\Api\Representation\SearchIndexRepresentation
     */
    public function index()
    {
        return $this->getAdapter('search_indexes')->getRepresentation($this->resource->getIndex());
    }

    /**
     * @return string
     */
    public function formAdapterName()
    {
        return $this->resource->getFormAdapter();
    }

    /**
     * @return \Search\FormAdapter\FormAdapterInterface|null
     */
    public function formAdapter()
    {
        $formAdapterName = $this->formAdapterName();
        $formAdapterManager = $this->getServiceLocator()->get('Search\FormAdapterManager');
        return $formAdapterManager->has($formAdapterName)
            ? $formAdapterManager->get($formAdapterName)
            : null;
    }

    /**
     * @return \Zend\Form\Form|null
     */
    public function form()
    {
        $formAdapter = $this->formAdapter();
        $formClass = $formAdapter->getFormClass();
        if (empty($formClass)) {
            return;
        }

        $services = $this->getServiceLocator();
        $formElementManager = $services->get('FormElementManager');

        $form = $formElementManager->get($formClass, [
            'search_page' => $this,
        ]);
        $form->setAttribute('method', 'GET');
        return $form;
    }

    /**
     * @return array
     */
    public function settings()
    {
        return $this->resource->getSettings();
    }

    /**
     * @return \DateTime
     */
    public function created()
    {
        return $this->resource->getCreated();
    }

    /**
     * @return \Search\Entity\SearchPage
     */
    public function getEntity()
    {
        return $this->resource;
    }
}
