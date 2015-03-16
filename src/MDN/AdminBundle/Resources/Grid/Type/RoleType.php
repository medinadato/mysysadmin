<?php

namespace MDN\AdminBundle\Resources\Grid\Type;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use APY\DataGridBundle\Grid\Source;
use APY\DataGridBundle\Grid\Column;
use APY\DataGridBundle\Grid\Action;
use APY\DataGridBundle\Grid\Export;

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
        
        $columns = array(
            new Column\NumberColumn(array(
                'id' => 'roleId',
                'field' => 'roleId',
                'filterable' => true,
                'source' => true,
                'title' => 'Id',
                    )),
            new Column\TextColumn(array(
                'id' => 'code',
                'field' => 'code',
                'source' => true,
                'title' => 'Code',
                    )),
            new Column\TextColumn(array(
                'id' => 'name',
                'field' => 'name',
                'filterable' => true,
                'source' => true,
                'title' => 'Display Name',
                    )),
            new Column\NumberColumn(array(
                'id' => 'number_users',
                'field' => 'number_users',
                'filterable' => true,
                'source' => true,
                'title' => 'Number of Users',
                    )),
            new Column\DateColumn(array(
                'id' => 'createdAt',
                'field' => 'createdAt',
                'source' => true,
                'title' => 'Created At',
                    )),
        );
        
        // source
        $source = new Source\Vector($rs, $columns);

        // set it
        $grid->setSource($source);
        $grid->setNoDataMessage(false);

        // Set the identifier of the grid
        $grid->setId('mdn_role');

        // Persist state
        $grid->setPersistence(true);

        // Exports
        $grid->addExport(new Export\XMLExport('XML Export', 'xml_export'));
        $grid->addExport(new Export\CSVExport('CSV Export', 'csv_export'));
        $grid->addExport(new Export\JSONExport('JSON Export', 'json_export'));

        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        # Columns
        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // Show/Hide columns
        $grid->setVisibleColumns(array('roleId', 'code', 'name', 'createdAt', 'number_users', 'enabled'));
        
        // Add a typed column with a rendering callback (status)
        $enabledColumn = new Column\TextColumn(array(
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

        // Set Default order
        $grid->setDefaultOrder('roleId', 'asc');
        

        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        # Row actions
        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // Edit
        $editRowAction = new Action\RowAction('Edit', 'mdn_admin_role_update', false, '_self');
        $editRowAction->setRouteParameters(array('roleId'));
        $editRowAction->setRouteParametersMapping(array('roleId' => 'id'));
        $grid->addRowAction($editRowAction);

        // return object
        return $grid;
    }

}
