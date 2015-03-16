<?php

namespace MDN\MySysBundle\Resources\Grid\Type;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use APY\DataGridBundle\Grid\Source;
use APY\DataGridBundle\Grid\Column;
use APY\DataGridBundle\Grid\Action;
use APY\DataGridBundle\Grid\Export;

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
                ->from('MDNMySysBundle:Server', 's')
                ->getQuery()
                ->getResult();

        $columns = array(
            new Column\NumberColumn(array(
                'id' => 'serverId',
                'field' => 'serverId',
                'filterable' => true,
                'source' => true,
                'title' => 'Id',
                    )),
            new Column\TextColumn(array(
                'id' => 'name',
                'field' => 'name',
                'source' => true,
                'title' => 'Server Name',
                    )),
            new Column\TextColumn(array(
                'id' => 'ip',
                'field' => 'ip',
                'filterable' => true,
                'source' => true,
                'title' => 'IP Address',
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
        $grid->setId('mdn_server');

        // Persist state
        $grid->setPersistence(true);

        // Exports
        $grid->addExport(new Export\XMLExport('XML Export', 'xml_export'));
        $grid->addExport(new Export\CSVExport('CSV Export', 'csv_export'));
        $grid->addExport(new Export\JSONExport('JSON Export', 'json_export'));

        // Set Default order
        $grid->setDefaultOrder('serverId', 'asc');

        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        # Row actions
        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // Edit
        $editRowAction = new Action\RowAction('Edit', 'mdn_my_sys_server_update', false, '_self');
        $editRowAction->setRouteParameters(array('serverId'));
        $editRowAction->setRouteParametersMapping(array('serverId' => 'id'));
        $grid->addRowAction($editRowAction);

        // DELETE
        $deleteRowAction = new Action\RowAction('Delete', 'mdn_my_sys_server_delete', true, '_self');
        $deleteRowAction->setRouteParameters(array('serverId'));
        $deleteRowAction->setRouteParametersMapping(array('serverId' => 'id'));
        
        $grid->addRowAction($deleteRowAction);
        
        return $grid;
    }

}
