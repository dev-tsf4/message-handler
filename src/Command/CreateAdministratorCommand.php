<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Administrator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateAdministratorCommand extends Command
{
    protected static $defaultName = 'app:create-administrator';
    protected static $defaultDescription = 'Create a new Administrator';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * CreateAdministratorCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $encoder
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $encoder,
        ValidatorInterface $validator
    ) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
        $this->validator = $validator;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('email', InputArgument::REQUIRED, 'The email')
            ->addArgument('password', InputArgument::REQUIRED, 'The password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $administrator = new Administrator();
        $administrator->setEmail($email);
        $administrator->setPassword($this->encoder->encodePassword($administrator, $password));

        $errors = $this->validator->validate($administrator);

        if (count($errors) > 0) {
            $output->writeln('<error>Vérifier l\'adresse mail et/ou votre mot de passe (255 caractères max)</error>');
            return 1;
        }

        $this->create($administrator);

        $output->writeln(
            sprintf('Création de l\'administrateur <comment>%s</comment> avec succès', $administrator->getEmail())
        );

        return 0;
    }

    private function create(Administrator $administrator)
    {
        $this->entityManager->persist($administrator);
        $this->entityManager->flush();
    }
}
