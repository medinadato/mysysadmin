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
class DomainType
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
        $rs = $qb->select('d.domainId, d.serverId, d.url, d.rootPath, d.hostConfigPath, d.createdAt')
                ->from('MDNMySysBundle:Domain', 'd')
                ->getQuery()
                ->getResult();

        $columns = array(
            new Column\NumberColumn(array(
                'id' => 'domainId',
                'field' => 'domainId',
                'filterable' => true,
                'source' => true,
                'title' => 'Id',
                    )),
            new Column\TextColumn(array(
                'id' => 'name',
                'field' => 'name',
                'source' => true,
                'title' => 'Domain Name',
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
        $grid->setId('mdn_domain');

        // Persist state
        $grid->setPersistence(true);

        // Exports
        $grid->addExport(new Export\XMLExport('XML Export', 'xml_export'));
        $grid->addExport(new Export\CSVExport('CSV Export', 'csv_export'));
        $grid->addExport(new Export\JSONExport('JSON Export', 'json_export'));

        // Set Default order
        $grid->setDefaultOrder('domainId', 'asc');

        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        # Row actions
        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // Edit
        $editRowAction = new Action\RowAction('Edit', 'mdn_my_sys_domain_update', false, '_self');
        $editRowAction->setRouteParameters(array('domainId'));
        $editRowAction->setRouteParametersMapping(array('domainId' => 'id'));
        $grid->addRowAction($editRowAction);
        
        // DELETE
        $deleteRowAction = new Action\RowAction('Delete', 'mdn_admin_user_delete', true, '_self');
        $deleteRowAction->setRouteParameters(array('userId'));
        $deleteRowAction->setRouteParametersMapping(array('userId' => 'id'));

        $deleteRowAction->manipulateRender(function ($action, $row) {
            if (!empty($row->getField('deletedAt'))) {
                return NULL;
            }

            return $action;
        });

        $grid->addRowAction($deleteRowAction);

        return $grid;
    }

}
