<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Restaurant;
use App\Entity\Diet;
use App\Entity\Specialty;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // Users
        $users = [];

        $admin = new User();
        $admin->setFullName("Administrateur d'EatYourWay")
            ->setPseudo(null)
            ->SetEmail('admin@eatyourway.fr')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setPlainPassword('Codemaster69@');

        $users[] = $admin;
        $manager->persist($admin);

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setFullName($this->faker->name())
                ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstName() : null)
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('Biguser69100$');

            $users[] = $user;
            $manager->persist($user);
        }

        
        
        // Contact
        for ($i = 0; $i < 5; $i++) {
            $contact = new Contact();
            $contact->setFullName($this->faker->name())
                ->setEmail($this->faker->email())
                ->setSubject('Demande n°' . ($i + 1))
                ->setMessage($this->faker->text());

            $manager->persist($contact);
        }

        // Diet
        $diets = [];
    for ($i = 0; $i < 5; $i++) {
        $name = 'Diet_' . $i;  // Nom unique basé sur l'indice
        $diet = new Diet();
        $diet->setName($name);
        $manager->persist($diet);
        $diets[] = $diet;
    }

        // Specialty
        $specialties = [];
        for ($i = 0; $i < 5; $i++) {
            $name = 'Specialty_' . $i;  // Nom unique basé sur l'indice
            $specialty = new Specialty();
            $specialty->setName($name);
            $manager->persist($specialty);
            $specialties[] = $specialty;
        }

        

        // Restaurants
        $restaurants = [];
        for ($j = 0; $j < 25; $j++) {
            $restaurant = new Restaurant();
            $restaurant->setName($this->faker->word())
                ->setDescription($this->faker->text(300))
                ->setIsPublic(mt_rand(0, 1) == 1 ? true : false)
                ->setUser($users[mt_rand(0, count($users) - 1)])
                ->setEmail($this->faker->companyEmail())
                ->setPhone($this->faker->phoneNumber())
                ->setStreetNumber($this->faker->buildingNumber())
                ->setStreet($this->faker->streetName())
                ->setZipCode($this->faker->postcode())
                ->setCity($this->faker->city())
                ->setDiet($diets[array_rand($diets)])
                ->setSpecialty($specialties[array_rand($specialties)]);
               $restaurants[] = $restaurant;
            $manager->persist($restaurant); 
            }

        $manager->flush();
    }
}

