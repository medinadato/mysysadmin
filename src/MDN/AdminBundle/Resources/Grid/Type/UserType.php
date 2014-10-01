<?php

namespace MDN\AdminBundle\Resources\Grid\Type;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use APY\DataGridBundle\Grid\Source;
use APY\DataGridBundle\Grid\Column;
use APY\DataGridBundle\Grid\Action;
use APY\DataGridBundle\Grid\Export;

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
        
        $columns = array(
            new Column\NumberColumn(array(
                'id' => 'userId',
                'field' => 'userId',
                'filterable' => true,
                'source' => true,
                'title' => 'Id',
                    )),
            new Column\TextColumn(array(
                'id' => 'username',
                'field' => 'username',
                'source' => true,
                'title' => 'Username',
                    )),
            new Column\NumberColumn(array(
                'id' => 'number_roles',
                'field' => 'number_roles',
                'filterable' => true,
                'source' => true,
                'title' => 'Number of Roles',
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

        $grid->setSource($source);
        $grid->setNoDataMessage(false);

        // Set the identifier of the grid
        $grid->setId('mdn_user');

        // Persist state
        $grid->setPersistence(true);

        // Exports
        $grid->addExport(new Export\XMLExport('XML Export', 'xml_export'));
        $grid->addExport(new Export\CSVExport('CSV Export', 'csv_export'));
        $grid->addExport(new Export\JSONExport('JSON Export', 'json_export'));

        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        # Mass Actions
        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // delete mass action
        $grid->addMassAction(new Action\DeleteMassAction());

        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        # Columns
        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // Set Default order
        $grid->setDefaultOrder('userId', 'asc');

        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        # Row actions
        # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // Edit
        $editRowAction = new Action\RowAction('Edit', 'mdn_admin_user_update', false, '_self');
        $editRowAction->setRouteParameters(array('userId'));
        $editRowAction->setRouteParametersMapping(array('userId' => 'id'));
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
        
        // return object
        return $grid;
    }

}
