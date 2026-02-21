<?php

declare(strict_types=1);

namespace App\Infrastructure\Admin;

use App\Infrastructure\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

/**
 * @extends AbstractAdmin<User>
 */
final class UserAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('email', EmailType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('email');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('email')->add(ListMapper::NAME_ACTIONS, null, [
            'actions' => [
                'show' => [],
                'edit' => [],
                'delete' => [],
            ],
        ]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('id')->add('email');
    }
}
