<?php

namespace MDN\AdminBundle\Controller;

use MDN\AdminBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Column\BlankColumn;
use APY\DataGridBundle\Grid\Column\DateColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Action\MassAction;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Action\RowAction;

/**
 * 
 */
class UserController extends Controller {

    /**
     * 
     * @return array
     * @Template("MDNAdminBundle:User:index.html.twig")
     */
    public function indexAction() {
//        $userRepo = $this->getRepository('MDNAdminBundle:User');
//        $users = $userRepo->findAll();
        // Creates simple grid based on your entity (ORM)
        $source = new Entity('MDNAdminBundle:User');
        /**
         * @var \APY\DataGridBundle\Grid\Grid $grid
         */
        $grid = $this->get('grid');
        $grid->setSource($source);

        // Set the identifier of the grid
        $grid->setId('mdn_user');

        $grid->addMassAction(new DeleteMassAction());

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


        // Add row actions in the default row actions column
        $editRowAction = new RowAction('Edit', 'mdn_admin_user_edit', false, '_self');
        $editRowAction->setRouteParameters(array( 'id' ) );
        $editRowAction->setRouteParametersMapping(array('userId' => 'id'));
        $grid->addRowAction($editRowAction);
        // Set default filters
        // Set the default order
        // Set the default page
        // Set max results
        // Set prefix titles
        // Add mass actions
        // Add row actions
        // Manipulate the query builder
        // Manipulate rows data
        // Manipulate columns
        // Manipulate column render cell
        // Set items per page selector
//        $grid->setLimits(array(5, 10, 15));
//        $grid->setPage(1);
        // Set the data for Entity and Document sources
        // Exports
//        $grid->setPersistence(true); 
        // Prepare data and the grid
        $grid->isReadyForRedirect();


        return $this->setTemplateParams(array(
                            'title' => 'User Edit',
                            'shortcuts' => array(
                                array('path' => 'mdn_admin_user_add', 'title' => 'Add New',),
                            ),
                        ))
                        ->renderTemplateParams(array(
                            'grid' => $grid,
        ));
    }

}
