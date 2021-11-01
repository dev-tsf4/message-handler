<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Administrator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdministratorFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * AdministratorFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadAdministrators($manager);
        $manager->flush();
    }

    private function loadAdministrators(ObjectManager $manager)
    {
        foreach ($this->getAdministratorsData() as [$email, $password, $roles]) {
            $administrator = new Administrator();
            $administrator->setEmail($email);
            $administrator->setPassword($this->encoder->encodePassword($administrator, $password));
            $administrator->setRoles($roles);

            $manager->persist($administrator);
        }
        $manager->flush();
    }

    private function getAdministratorsData(): array
    {
        return [
          ['admin@mail.com', 'admin', ['ROLE_ADMIN']],
          ['johndoe@mail.com', 'secret123', ['ROLE_ADMIN']]
        ];
    }
}
