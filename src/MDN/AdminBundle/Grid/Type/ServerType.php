<?php

namespace MDN\AdminBundle\Grid\Type;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use APY\DataGridBundle\Grid\Source\Vector;
use APY\DataGridBundle\Grid\Column\BlankColumn;
use APY\DataGridBundle\Grid\Column\DateColumn;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Column\NumberColumn;
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
class ServerType
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
        $rs = $qb->select('s.serverId, s.name, s.ip, s.createdAt')
//                ->addSelect("COUNT(u) number_users")
                ->from('MDNAdminBundle:Server', 's')
//                ->leftJoin('r.user', 'u')
//                ->groupBy('r.roleId')
                ->getQuery()
                ->getResult();

        $columns = array(
            new NumberColumn(array(
                'id' => 'serverId',
                'field' => 'serverId',
                'filterable' => true,
                'source' => true,
                'title' => 'Id',
                    )),
            new TextColumn(array(
                'id' => 'name',
                'field' => 'name',
                'source' => true,
                'title' => 'Server Name',
                    )),
            new NumberColumn(array(
                'id' => 'ip',
                'field' => 'ip',
                'filterable' => true,
                'source' => true,
                'title' => 'IP Address',
                    )),
            new DateColumn(array(
                'id' => 'createdAt',
                'field' => 'createdAt',
                'source' => true,
                'title' => 'Created At',
                    )),
        );

        // source
        $source = new Vector($rs, $columns);

        // set it
        $grid->setSource($source);

        // Set the identifier of the grid
        $grid->setId('mdn_server');

        // Persist state
        $grid->setPersistence(true);

        // Exports
        $grid->addExport(new XMLExport('XML Export', 'xml_export'));
        $grid->addExport(new CSVExport('CSV Export', 'csv_export'));
        $grid->addExport(new JSONExport('JSON Export', 'json_export'));

        // Set Default order
        $grid->setDefaultOrder('serverId', 'asc');


        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        # Row actions
        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // Edit
        $editRowAction = new RowAction('Edit', 'mdn_admin_server_index', false, '_self');
        $editRowAction->setRouteParameters(array('serverId'));
        $editRowAction->setRouteParametersMapping(array('serverId' => 'id'));
        $grid->addRowAction($editRowAction);

        return $grid;
    }

}
