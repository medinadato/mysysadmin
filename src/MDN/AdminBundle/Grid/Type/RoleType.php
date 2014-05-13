<?php

namespace MDN\AdminBundle\Grid\Type;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
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

/**
 * 
 */
class RoleType
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
        /** @var $grid \APY\DataGridBundle\Grid\Grid */
        $grid = $this->container->get('grid');

        // runs the search
        $qb = $this->container->get('doctrine')->getManager()->createQueryBuilder();
        $rs = $qb->select('r.roleId, r.code, r.name, r.createdAt, r.deletedAt')
                ->addSelect("COUNT(u) number_users")
                ->from('MDNAdminBundle:Role', 'r')
                ->leftJoin('r.user', 'u')
                ->groupBy('r.roleId')
                ->getQuery()
                ->getResult();

        // source
        $source = new Vector($rs);

        // set it
        $grid->setSource($source);

        // Set the identifier of the grid
        $grid->setId('mdn_role');

        // Persist state
        $grid->setPersistence(true);

        // Exports
        $grid->addExport(new XMLExport('XML Export', 'xml_export'));
        $grid->addExport(new CSVExport('CSV Export', 'csv_export'));
        $grid->addExport(new JSONExport('JSON Export', 'json_export'));

        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        # Columns
        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // Show/Hide columns
        $grid->setVisibleColumns(array('roleId', 'code', 'name', 'createdAt', 'number_users', 'enabled'));
        
        // Add a typed column with a rendering callback (status)
        $enabledColumn = new TextColumn(array(
            'id' => 'enabled',
            'title' => 'Enabled',
            'sortable' => false,
            'filterable' => true,
            'source' => false,
        ));

        $enabledColumn->manipulateRenderCell(function($value, $row, $router) {
            return (empty($row->getField('deletedAt'))) ? 'Yes' : 'No';
        });
        $grid->addColumn($enabledColumn);

        // customizes columns
        $grid->getColumn('roleId')
                ->setTitle('id');
        $grid->getColumn('code')
                ->setTitle('Code');
        $grid->getColumn('name')
                ->setTitle('Display Name');
        $grid->getColumn('createdAt')
                ->setTitle('Created At');
        $grid->getColumn('number_users')
                ->setTitle('Number of Users');

        // Set Default order
        $grid->setDefaultOrder('roleId', 'asc');
        

        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        # Row actions
        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // Edit
        $editRowAction = new RowAction('Edit', 'mdn_admin_role_update', false, '_self');
        $editRowAction->setRouteParameters(array('roleId'));
        $editRowAction->setRouteParametersMapping(array('roleId' => 'id'));
        $grid->addRowAction($editRowAction);

        return $grid;
    }

}
