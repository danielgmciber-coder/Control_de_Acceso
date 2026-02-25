<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class UserCrudController extends AbstractCrudController
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // El ID no se debe poder editar
        yield IdField::new('id')->hideOnForm();

        // Asumo que tienes un campo email o username. Cámbialo si tu campo se llama distinto.
        yield TextField::new('email', 'Email');

        // Aquí configuramos el campo de la contraseña en texto plano
        yield TextField::new('plainPassword', 'Contraseña')
            ->onlyOnForms() // Para que no se muestre la contraseña en el listado, solo al editar/crear
            ->setFormType(PasswordType::class) // Convierte el input en tipo "password" (asteriscos)
            ->setRequired($pageName === Crud::PAGE_NEW); // Es obligatorio al crear, pero opcional al editar
        yield ChoiceField::new('roles', 'Roles')
                ->setChoices([
                        'Administrador' => 'ROLE_ADMIN',
                        'Usuario Normal' => 'ROLE_USER',
                ])
                ->allowMultipleChoices() // Permite seleccionar varios roles a la vez
                ->renderExpanded(); // Lo muestra como casillas de verificación (checkboxes)
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->hashPassWord($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->hashPassWord($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function hashPassWord($entity): void
    {
        if (!($entity instanceof User)) {
            return;
        }

        // Solo encriptamos si el usuario ha escrito algo en el campo plainPassword
        if ($entity->getPlainPassword()) {
            $hashedPassword = $this->userPasswordHasher->hashPassword($entity, $entity->getPlainPassword());
            $entity->setPassword($hashedPassword);
        }
    }
}
