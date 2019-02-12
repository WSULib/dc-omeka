<?php
namespace Search\Service\Form;

use Interop\Container\ContainerInterface;
use Search\Form\Admin\SearchIndexForm;
use Zend\ServiceManager\Factory\FactoryInterface;

class SearchIndexFormFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
    {
        $searchAdapterManager = $services->get('Search\AdapterManager');

        $form = new SearchIndexForm(null, $options);
        $form->setSearchAdapterManager($searchAdapterManager);
        return $form;
    }
}
