<?php

namespace MDN\AdminBundle\Grid\Type;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Source\Vector;
use APY\DataGridBundle\Grid\Column\BlankColumn;
use APY\DataGridBundle\Grid\Column\DateColumn;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Action\MassAction;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Export\XMLExport;
use APY\DataGridBundle\Grid\Export\CSVExport;
use APY\DataGridBundle\Grid\Export\JSONExport;

class UserType 
{
    /**
     *
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;
    
    /**
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    /**
     * 
     * @return \APY\DataGridBundle\Grid\Grid
     */
    public function build()
    {
        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        # Start grid
        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        /** @var \APY\DataGridBundle\Grid\Grid $grid */
        $grid = $this->container->get('grid');
        
        // Creates simple grid based on your entity (ORM)
        $source = new Entity('MDNAdminBundle:User');
        $grid->setSource($source);

        // Set the identifier of the grid
        $grid->setId('mdn_user');
        
        // Persist state
        $grid->setPersistence(true);
        
        // Exports
        $grid->addExport(new XMLExport('XML Export', 'xml_export'));
        $grid->addExport(new CSVExport('CSV Export', 'csv_export'));
        $grid->addExport(new JSONExport('JSON Export', 'json_export'));

        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        # Mass Actions
        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // delete mass action
        $grid->addMassAction(new DeleteMassAction());

        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        # Columns
        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // Add a typed column with a rendering callback
        $statusColumn = new TextColumn(array(
                    'id' => 'status',
                    'title' => 'Status',
                    'sortable' => false,
                    'filterable' => true,
                    'source' => false,
                ));
        $statusColumn->manipulateRenderCell(function($value, $row, $router) {
                    return (empty($row->getField('deletedAt'))) ? 'Active' : 'Inactive';
                });
        $grid->addColumn($statusColumn);

        // Show/Hide columns
        $grid->setVisibleColumns(array('userId', 'username', 'createdAt', 'status'));
        
        // Set Default order
        $grid->setDefaultOrder('userId', 'asc');

        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        # Row actions
        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // Edit
        $editRowAction = new RowAction('Edit', 'mdn_admin_user_update', false, '_self');
        $editRowAction->setRouteParameters(array('userId'));
        $editRowAction->setRouteParametersMapping(array('userId' => 'id'));
        $grid->addRowAction($editRowAction);
        
        // DELETE
        $deleteRowAction = new RowAction('Delete', 'mdn_admin_user_delete', false, '_self');
        $deleteRowAction->setRouteParameters(array('userId'));
        $deleteRowAction->setRouteParametersMapping(array('userId' => 'id'));
        $deleteRowAction->manipulateRender(
            function ($action, $row)
            {
                if (!empty($row->getField('deletedAt'))) {
                    return NULL;
                }

                return $action;
            }
        );
        $grid->addRowAction($deleteRowAction);

        // Set default filters
        // Set prefix titles
        // Add mass actions
        // Add row actions
        // Manipulate the query builder
        // Manipulate rows data
        // Manipulate columns
        // Manipulate column render cell
        // Set items per page selector
      
        return $grid;
    }

}