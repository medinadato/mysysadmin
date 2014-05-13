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

        /** @var Doctrine\ORM\QueryBuilder $query_builder */
        $query_builder = $this->container->get('doctrine')->getManager()->createQueryBuilder();
        $rs = $query_builder->select('u.userId, u.username, u.createdAt')
                ->addSelect("COUNT(r) number_roles")
                ->from('MDNAdminBundle:User', 'u')
                ->leftJoin('u.role', 'r')
                ->where('u.deletedAt IS NULL')
                ->groupBy('u.userId')
                ->getQuery()
                ->getResult();
                
        // source
        $source = new Vector($rs);

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
        // Show/Hide columns
        $grid->setVisibleColumns(array('userId', 'username', 'createdAt', 'number_roles'));

        // Set Default order
        $grid->setDefaultOrder('userId', 'asc');
        
        // customizes columns
        $grid->getColumn('userId')
                ->setTitle('Id');
        $grid->getColumn('username')
                ->setTitle('Username');
        $grid->getColumn('createdAt')
                ->setTitle('Created At');
        $grid->getColumn('number_roles')
                ->setTitle('Number of Roles');

        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        # Row actions
        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // Edit
        $editRowAction = new RowAction('Edit', 'mdn_admin_user_update', false, '_self');
        $editRowAction->setRouteParameters(array('userId'));
        $editRowAction->setRouteParametersMapping(array('userId' => 'id'));
        $grid->addRowAction($editRowAction);

        // DELETE
        $deleteRowAction = new RowAction('Delete', 'mdn_admin_user_delete', true, '_self');
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
